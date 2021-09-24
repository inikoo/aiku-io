<?php

namespace App\Actions\HumanResources\Employee;

use App\Models\HumanResources\Employee;
use App\Rules\Phone;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateEmployee
{
    use AsAction;

    public function handle(Employee $employee, array $contactData, array $employeeData): Employee
    {
        $employee->contact()->update($contactData);
        $employee->update($employeeData);

        return $employee;
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

    public function asController(Employee $employee, ActionRequest $request): Employee
    {
        return $this->handle(
            $employee,
            $request->only('name', 'email', 'phone'),
            $request->only('status')
        );
    }

}
