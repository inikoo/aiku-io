<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Sep 2021 02:39:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Models\HumanResources\Employee;
use App\Rules\Phone;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreEmployee
{
    use AsAction;

    /**
     * @param  array  $contactData
     * @param  array  $employeeData
     *
     * @return \App\Models\HumanResources\Employee
     */
    public function handle(array $contactData, array $employeeData): Employee
    {

        $employee = Employee::create($employeeData);
        $employee->contact()->create($contactData);
        $employee->save();

        return $employee;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('employee:store');
    }

    public function rules(): array
    {
        return [
            'name'   => 'required|string',
            'email'  => 'email',
            'phone'  => ['string', new Phone()],
            'status' => [
                'required',
                Rule::in(['working', 'ex-employee', 'hired']),
            ]
        ];
    }

    public function asController(ActionRequest $request): Employee
    {
        return $this->handle(
            $request->only('name', 'email', 'phone'),
            $request->only('status')
        );
    }
}
