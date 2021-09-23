<?php

namespace App\Console\Commands\AuroraMigration;

use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;
use App\Models\Aiku\Tenant;
use App\Models\HumanResources\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EmployeesAurora extends Command
{

    use AuroraMigratory;

    protected $signature = 'au_migration:employees {--reset} {--all} {--t|tenant=* : Tenant slug}';
    protected $description = 'Migrate aurora employees/users';

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
          //  print_r($auroraData);

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

            if ($auroraData->aiku_id) {
                $employee = Employee::find($auroraData->aiku_id);

                if ($employee->id) {
                     $employee = UpdateEmployee::run($employee, $contactData, $employeeData);


                    $changes = $employee->getChanges();


                    if (count($changes) > 0) {
                        $this->results[$tenant->slug]['updated']++;
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
        }
    }


}
