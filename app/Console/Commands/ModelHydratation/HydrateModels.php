<?php

namespace App\Console\Commands\ModelHydratation;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class HydrateModels extends Command
{

    protected $signature = 'hydrate:all {--m|model=* : Model} {--t|tenant=* : Tenant nickname}';


    protected $description = 'Hydrate all denormalized models';

    private array $models;


    public function __construct()
    {
        parent::__construct();
        $this->models=['user','employee','guest','warehouse','warehouse_area'];
    }

    public function handle(): int
    {


        foreach(

            count($this->option('model'))?
                array_intersect($this->models,$this->option('model')):
                $this->models

            as $model){
            $this->line("Hydrating $model");

            Artisan::call("hydrate:$model",
                          [
                              'id' => 'all',
                              '--tenant'  => $this->option('tenant')
                          ]
            );
        }



        return 0;
    }

    /*
    protected function user(){
        $this->line("Hydrating users");
        Artisan::call("hydrate:user",
                      [
                          'user_id' => 'all',
                          '--tenant'  => $this->option('tenant')
                      ]
        );
    }

    protected function employee(){
        $this->line("Hydrating employees");
        Artisan::call("hydrate:employee",
                      [
                          'employee_id' => 'all',
                          '--tenant'  => $this->option('tenant')
                      ]
        );
    }
    */


}
