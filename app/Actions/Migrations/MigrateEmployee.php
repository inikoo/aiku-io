<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 13:48:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;
use App\Actions\System\User\StoreUser;
use App\Actions\System\User\UpdateUser;
use App\Models\HumanResources\Employee;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateEmployee
{
    use AsAction;
    use MigrateAurora;

    public function handle($auroraData): array
    {
        $result = [
            'updated'  => 0,
            'inserted' => 0,
            'errors'   => 0
        ];

        //print_r($auroraData);
        $contactData = [
            'name'                     => $auroraData->{'Staff Name'},
            'email'                    => $auroraData->{'Staff Email'},
            'phone'                    => $auroraData->{'Staff Telephone'},
            'identity_document_number' => $auroraData->{'Staff Official ID'},
            'date_of_birth'            => $auroraData->{'Staff Birthday'}
        ];

        $contactData = $this->sanitizeData($contactData);


        $employeeData = [
            'nickname'            => strtolower($auroraData->{'Staff Alias'}),
            'worker_number'       => $auroraData->{'Staff ID'},
            'employment_start_at' => $this->getDate($auroraData->{'Staff Valid From'}),
            'employment_end_at'   => $this->getDate($auroraData->{'Staff Valid To'}),
            'type'                => $auroraData->{'Staff Type'},
            'aurora_id'           => $auroraData->{'Staff Key'},
            'state'               => match ($auroraData->{'Staff Currently Working'}) {
                'No' => 'Left',
                default => 'Working'
            },
            'data'                => [
                'address' => $auroraData->{'Staff Address'},
            ]
        ];

        $employeeData = $this->sanitizeData($employeeData);



        $updated = false;

        if ($auroraData->aiku_id) {
            $employee = Employee::find($auroraData->aiku_id);

            if ($employee) {
                $employee = UpdateEmployee::run($employee, $contactData, $employeeData);


                $changes = $employee->getChanges();
                if (count($changes) > 0) {
                    $updated = true;
                }
            } else {
                $result['errors']++;
                DB::connection('aurora')->table('Staff Dimension')
                    ->where('Staff Key', $auroraData->{'Staff Key'})
                    ->update(['aiku_id' => null]);
            }
        } else {
            try {
                $employee = StoreEmployee::run($contactData, $employeeData);
                if ($employee) {
                    DB::connection('aurora')->table('Staff Dimension')
                        ->where('Staff Key', $auroraData->{'Staff Key'})
                        ->update(['aiku_id' => $employee->id]);


                    $result['inserted']++;
                }
            }catch (Exception){
                $result['errors']++;
                return $result;
            }

        }

        if ($auroraUserData = DB::connection('aurora')->table('User Dimension')
            ->where('User Type', 'Staff')->where('User Parent Key', $auroraData->{'Staff Key'})
            ->first()) {
            //print_r($auroraUserData);


            $userData = [
                'username'    => strtolower($auroraUserData->{'User Handle'}),
                'password'    => Hash::make(config('app.env') == 'local' ? 'hello' : wordwrap(Str::random(), 4, '-', true)),
                'aurora_id'   => $auroraUserData->{'User Key'},
                'language_id' => $this->parseLanguageID($auroraUserData->{'User Preferred Locale'}),
                'status'      => $auroraUserData->{'User Active'} == 'Yes' ? 'Active' : 'Suspended'
            ];

            if ($employee->user) {
                $user = UpdateUser::run($employee->user, $userData);
                if (count($user->getChanges()) > 0) {
                    $updated = true;
                }
            } else {
                // print_r($userData);
                StoreUser::run($employee, $userData, []);
            }
        }

        if ($updated) {
            $result['updated']++;
        }

        return $result;

    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraModelID): array
    {
        $this->set_aurora_connection(app('currentTenant')->data['aurora_db']);
        $auroraData = DB::connection('aurora')->table('Employee Dimension')->where('Employee Key', $auroraModelID)->get();

        return $this->handle($auroraData);
    }
}
