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
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\JobPosition;
use App\Models\HumanResources\Workplace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;

class MigrateEmployee extends MigrateModel
{

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Staff Dimension';
        $this->auModel->id_field = 'Staff Key';
    }

    public function getParent(): Workplace|null
    {
        /** @var \App\Models\Account\Tenant $tenant */
        $tenant = App('currentTenant');
        return $tenant->workplaces()->where('type', 'hq')->first();
    }

    public function parseModelData()
    {



        $data   = [];
        $errors = [];
        if ($this->getDate($this->auModel->data->{'Staff Valid From'}) == '') {
            $errors = [
                'missing' => ['created_at', 'employment_start_at']
            ];
        }


        $working_hours = json_decode($this->auModel->data->{'Staff Working Hours'}, true);
        if ($working_hours) {
            $working_hours['week_distribution'] = array_change_key_case(
                json_decode($this->auModel->data->{'Staff Working Hours Per Week Metadata'}, true)
                ,
                CASE_LOWER
            );
        }


        $workingHours     = json_decode($this->auModel->data->{'Staff Working Hours'}, true);
        $weekDistribution = json_decode($this->auModel->data->{'Staff Working Hours Per Week Metadata'}, true);

        if ($workingHours and $weekDistribution) {
            $workingHours['week_distribution'] = array_change_key_case($weekDistribution, CASE_LOWER);
        }


        $salary = json_decode($this->auModel->data->{'Staff Salary'}, true);
        if ($salary) {
            $salary = array_change_key_case($salary, CASE_LOWER);
        }

        if ($this->auModel->data->{'Staff Address'}) {
            $data['address'] = $this->auModel->data->{'Staff Address'};
        }

        $this->modelData['employee'] = $this->sanitizeData(
            [
                'name'                     => $this->auModel->data->{'Staff Name'},
                'email'                    => $this->auModel->data->{'Staff Email'},
                'phone'                    => $this->auModel->data->{'Staff Telephone'},
                'identity_document_number' => $this->auModel->data->{'Staff Official ID'},
                'date_of_birth'            => $this->auModel->data->{'Staff Birthday'},
                'worker_number'       => $this->auModel->data->{'Staff ID'},
                'nickname'            => strtolower($this->auModel->data->{'Staff Alias'}),
                'employment_start_at' => $this->getDate($this->auModel->data->{'Staff Valid From'}),
                'created_at'          => $this->auModel->data->{'Staff Valid From'},
                'emergency_contact'   => $this->auModel->data->{'Staff Next of Kind'},
                'job_title'           => $this->auModel->data->{'Staff Job Title'},
                'salary'              => $salary,
                'working_hours'       => $workingHours,

                'employment_end_at' => $this->getDate($this->auModel->data->{'Staff Valid To'}),
                'type'              => Str::snake($this->auModel->data->{'Staff Type'}, '-'),
                'aurora_id'         => $this->auModel->data->{'Staff Key'},
                'state'             => match ($this->auModel->data->{'Staff Currently Working'}) {
                    'No' => 'left',
                    default => 'working'
                },
                'data'              => $data,
                'errors'            => $errors
            ]
        );


        $this->auModel->id = $this->auModel->data->{'Staff Key'};
    }


    public function setModel()
    {
        $this->model = Employee::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateEmployee::run($this->model,  $this->modelData['employee']);
    }

    public function storeModel(): ActionResult
    {
        return StoreEmployee::run(workplace: $this->parent,  modelData: $this->modelData['employee']);
    }

    protected function migrateImages()
    {
        /** @var Employee $employee */
        $employee = $this->model;


        $auroraImagesCollection          = $this->getModelImagesCollection('Staff', $employee->aurora_id);
        $auroraImagesCollectionWithImage = $auroraImagesCollection->each(function ($auroraImage) {
            $rawImage = MigrateRawImage::run($auroraImage);
            if ($rawImage) {
                return $auroraImage->communal_image_id = $rawImage->communalImage->id;
            } else {
                return $auroraImage->communal_image_id = null;
            }
        });

        if ($auroraImagesCollectionWithImage->count()) {
            MigrateImages::run($employee, $auroraImagesCollectionWithImage);
        }
    }

    protected function migrateAttachments()
    {
        /** @var Employee $employee */
        $employee = $this->model;

        $auroraAttachmentsCollection               = $this->getModelAttachmentsCollection('Staff', $employee->aurora_id);
        $auroraAttachmentsCollectionWithAttachment = $auroraAttachmentsCollection->each(function ($auroraAttachment) {
            if ($attachment = MigrateCommonAttachment::run($auroraAttachment)) {
                return $auroraAttachment->common_attachment_id = $attachment->id;
            } else {
                return $auroraAttachment->common_attachment_id = null;
            }
        });

        MigrateAttachments::run($employee, $auroraAttachmentsCollectionWithAttachment);
    }


    public function postMigrateActions(ActionResult $res): ActionResult
    {
        /** @var Employee $employee */
        $employee = $this->model;


        $jobPositions = [];

        foreach (
            DB::connection('aurora')
                ->table('Staff Role Bridge')
                ->where('Staff Key', $employee->aurora_id)
                ->get() as $auroraData
        ) {
            $roleCode = match ($auroraData->{'Role Code'}) {
                'WAHM' => 'wah-m',
                'WAHSK' => 'wah-sk',
                'WAHSC' => 'wah-sc',
                'PICK' => 'dist-pik',
                'OHADM' => 'dist-m',
                'PRODM' => 'prod-m',
                'PRODO' => 'prod-w',
                'CUSM' => 'cus-m',
                default => strtolower($auroraData->{'Role Code'})
            };

            if ($jobPosition = JobPosition::firstWhere('slug', $roleCode)) {
                $jobPositions[] = $jobPosition->id;
            }

            if ($roleCode == 'dist-pik') {
                $jobPosition = JobPosition::firstWhere('slug', 'dist-pak');
                if ($jobPosition) {
                    $jobPositions[] = $jobPosition->id;
                }
            }
            $employee->jobPositions()->sync($jobPositions);
        }




        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }


    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        $auroraData = DB::connection('aurora')->table('Staff Dimension')->where('Staff Key', $auroraID)->first();
        if ($auroraData) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}
