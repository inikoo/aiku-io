<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Sep 2021 02:39:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\Auth\User\StoreUser;
use App\Models\Auth\User;
use App\Models\HumanResources\JobPosition;
use App\Models\HumanResources\Workplace;
use App\Models\Utils\ActionResult;
use App\Models\HumanResources\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;


class StoreEmployee
{
    use AsAction;
    use WithAttributes;


    public function handle(?Workplace $workplace, array $modelData): ActionResult
    {
        $res = new ActionResult();





        $employeeData = Arr::except($modelData, ['create_user', 'job_positions']);

        /** @var Employee $employee */
        if ($workplace) {
            $employee = $workplace->employees()->create($employeeData);
        } else {
            $employee = Employee::create($employeeData);
        }

        if (Arr::exists($modelData, 'job_positions')) {
            $res = UpdateEmployeeJobPositions::run(
                employee:  $employee,
                operation: Arr::get($modelData, 'job_positions.operation'),
                ids:       Arr::get($modelData, 'job_positions.ids'),

            );

            $employee->update(['job_position_scopes' => Arr::get($modelData, 'job_positions.scopes')]);
        }

        $employee->save();





        if (Arr::exists($modelData, 'create_user') and $modelData['create_user']) {
            $middleware_group = app('currentTenant')->domain ? 'standalone' : 'jar';

            $this->getUsername($employee->nickname, $middleware_group);


             StoreUser::run(
                userable: $employee,
                userData: [
                              'username'         => $employee->nickname,
                              'password'         => Hash::make(config('app.env') == 'local' ? 'hello' : wordwrap(Str::random(), 4, '-', true)),
                              'middleware_group' => $middleware_group
                          ]
            );
        }

        $res->model    = $employee;
        $res->model_id = $employee->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';


        return $res;
    }

    public function getUsername($username, $middleware_group)
    {
        if ($middleware_group == 'standalone') {
            $suffix = 1;


            while ($suffix < 10000) {
                $result = $this->findUsername($username, app('currentTenant')->id, $suffix);

                if ($result['username']) {
                    $username = $result['username'];
                    break;
                }

                $suffix++;
            }

            return $username;
        }
        return $username;
    }

    public function findUsername($username, $tenant_id, $suffix = ''): array
    {
        if (preg_match('/(.+)-.*$/', $username, $matches)) {
            $username = $matches[1];
        }

        if ($suffix > 1) {
            $suffix = '-'.$suffix;
        } else {
            $suffix = '';
        }
        /** @var User $user */
        if ($user = User::withTrashed()->where('username', $username.$suffix)->where('tenant_id', $tenant_id)->first()) {
            if ($user->id) {
                return [
                    'username' => false
                ];
            }
        }

        return [
            'username' => $username.$suffix
        ];
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('employee:store')
            || $request->user()->hasPermissionTo("employees.edit");
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if ($request->exists('job_positions')) {
            $rawData = $request->get('job_positions');

            $positionIds = [];
            foreach (Arr::get($rawData, 'positions', []) as $slug => $value) {
                if ($value && $position = JobPosition::where('slug', $slug)->first()) {
                    $positionIds[] = $position->id;
                }
            }


            $request->merge([
                                'job_positions' =>
                                    [
                                        'operation' => 'sync',
                                        'ids'       => $positionIds,
                                        'scopes'    => $rawData['scopes']
                                    ],


                            ]);
        }
    }


    public function rules(): array
    {
        return [
            'name'                     => 'required|string',
            'email'                    => 'email',
            'phone'                    => 'phone:AUTO',
            'identity_document_number' => 'required|string',
            'date_of_birth'            => 'nullable|date|before_or_equal:today',
            'address'                  => 'nullable|string',
            'emergency_contact'        => 'nullable|string',
            'nickname'                 => 'required|string',
            'worker_number'            => 'required|string',
            'create_user'              => 'required|boolean'


        ];
    }


    public function asController(ActionRequest $request): ActionResult
    {
        $request->validate();

        return $this->handle(
            workplace: null,
            modelData: $request->only('status', 'name', 'email', 'phone')
        );
    }

    public function asInertia(Request $request): RedirectResponse
    {
        $this->fillFromRequest($request);
        $this->validateAttributes();

        $employeeData = $request->only(
            'name',
            'email',
            'phone',
            'identity_document_number',
            'date_of_birth',
            'nickname',
            'worker_number',
            'emergency_contact',
            'job_positions',
            'create_user'
        );

        $res = $this->handle(
            null,
            $employeeData

        );


        return Redirect::route('human_resources.employees.show', $res->model_id);
    }

}
