<?php

namespace App\Actions\HumanResources\Employee;

use App\Actions\Migrations\MigrationResult;
use App\Models\HumanResources\Employee;
use App\Rules\Phone;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateEmployee
{
    use AsAction;

    public function handle(Employee $employee, array $contactData, array $employeeData): MigrationResult
    {

        $res = new MigrationResult();

        $employee->contact()->update($contactData);
        $res->changes=array_merge($res->changes,$employee->contact->getChanges());

        $employee->update($employeeData);
        $res->changes=array_merge($res->changes,$employee->getChanges());

        $res->model=$employee;

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('human-resources:edit');
    }

    public function rules(): array
    {
        return [
            'name'   => 'sometimes|required|string',
            'email'  => 'sometimes|email',
            'phone'  => ['string', new Phone()],
            'status' => [
                'sometimes',
                'required',
                Rule::in(['working', 'ex-employee', 'hired']),
            ]
        ];
    }

    public function asController(Employee $employee, ActionRequest $request): MigrationResult
    {
        return $this->handle(
            $employee,
            $request->only('name', 'email', 'phone'),
            $request->only('status')
        );
    }

}
