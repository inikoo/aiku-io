<?php

namespace App\Http\Controllers\HumanResources;


use App\Actions\HumanResources\Employee\IndexEmployee;
use App\Actions\HumanResources\Employee\ShowCreateEmployee;
use App\Actions\HumanResources\Employee\ShowEditEmployee;
use App\Actions\HumanResources\Employee\ShowEmployee;
use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;
use App\Http\Controllers\Controller;
use App\Models\HumanResources\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;


class EmployeeController extends Controller
{

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

    public function create(): Response
    {
        return ShowCreateEmployee::make()->asInertia();
    }

    public function store(Request $request): RedirectResponse
    {
        return StoreEmployee::make()->asInertia($request);
    }

    public function update(Employee $employee, Request $request): RedirectResponse
    {
        return UpdateEmployee::make()->asInertia($employee, $request);
    }


}
