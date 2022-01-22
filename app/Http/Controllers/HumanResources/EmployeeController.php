<?php

namespace App\Http\Controllers\HumanResources;


use App\Actions\HumanResources\Employee\IndexEmployee;
use App\Actions\HumanResources\Employee\ShowEditEmployee;
use App\Actions\HumanResources\Employee\ShowEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;
use App\Models\HumanResources\Employee;
use App\Http\Controllers\Traits\HasContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;


class EmployeeController extends HumanResourcesController
{
    use HasContact;

    private array $identityDocumentTypes;
    private mixed $defaultCountry;




    public function index(): Response
    {


        return IndexEmployee::make()->asInertia();
    }

    public function show(Employee $employee): Response
    {
        return ShowEmployee::make()->asInertia($employee);
    }

    public function edit(Employee $employee): Response
    {
        return ShowEditEmployee::make()->asInertia($employee);
    }

    public function update(Employee $employee, Request $request): RedirectResponse
    {
        return UpdateEmployee::make()->asInertia($employee, $request);
    }


}
