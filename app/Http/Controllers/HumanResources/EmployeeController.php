<?php

namespace App\Http\Controllers\HumanResources;


use App\Actions\HumanResources\Employee\IndexEmployee;
use App\Actions\HumanResources\Employee\ShowEmployee;
use App\Actions\System\User\ShowEditUser;
use App\Actions\System\User\UpdateUser;
use App\Models\HumanResources\Employee;
use App\Http\Controllers\Traits\HasContact;
use App\Models\System\User;
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

    public function edit(User $user): Response
    {
        return ShowEditUser::make()->asInertia($user);
    }

    public function update(User $user, Request $request): RedirectResponse
    {
        return UpdateUser::make()->asInertia($user, $request);
    }


}
