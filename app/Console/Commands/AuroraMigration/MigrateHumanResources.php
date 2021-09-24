<?php

namespace App\Console\Commands\AuroraMigration;

use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;
use App\Actions\System\User\StoreUser;
use App\Actions\System\User\UpdateUser;
use App\Models\Account\Tenant;
use App\Models\HumanResources\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MigrateHumanResources extends Command
{

    use AuroraMigratory;

    protected $signature = 'au_migration:hr {--reset} {--all} {--t|tenant=* : Tenant slug}';
    protected $description = 'Migrate aurora human resources';

    private array $results;


    public function __construct()
    {
        parent::__construct();
    }


    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    private function reset()
    {
        DB::connection('aurora')->table('Staff Dimension')
            ->update(['aiku_id' => null]);
    }

    private function migrate(Tenant $tenant)
    {
        foreach (DB::connection('aurora')->table('Staff Dimension')->get() as $auroraData) {
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

            $this->results[$tenant->slug]['models']++;

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
                    $this->results[$tenant->slug]['errors']++;
                    DB::connection('aurora')->table('Staff Dimension')
                        ->where('Staff Key', $auroraData->{'Staff Key'})
                        ->update(['aiku_id' => null]);
                }
            } else {
                $employee = StoreEmployee::run($contactData, $employeeData);
                if ($employee->id) {
                    DB::connection('aurora')->table('Staff Dimension')
                        ->where('Staff Key', $auroraData->{'Staff Key'})
                        ->update(['aiku_id' => $employee->id]);


                    $this->results[$tenant->slug]['inserted']++;
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
                $this->results[$tenant->slug]['updated']++;
            }
        }
    }


}
