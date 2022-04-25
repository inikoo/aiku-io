<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 06 Apr 2022 15:52:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

return [

    'positions' => [

        'dir'   => [
            'slug'  => 'dir',
            'name'  => 'director',
            'roles' => [

            ]
        ],
        'hr-m'  => [
            'slug'  => 'hr-m',
            'grade' => 'manager',
            'name'  => 'Human resources supervisor',
            'roles' => [
                'human-resources-admin'
            ]
        ],
        'hr-c'  => [
            'slug'  => 'hr-c',
            'name'  => 'Human resources clerk',
            'grade' => 'clerk',
            'roles' => [
                'human-resources-clerk'
            ]
        ],
        'acc'   => [
            'slug'  => 'acc',
            'name'  => 'Accounts',
            'roles' => [

            ]
        ],

        'rec-c'=>[
            'slug'       => 'rec-c',
            'grade' => 'clerk',
            'team'       => 'rec',
            'department' => 'recruiting',
            'name'       => 'Recruiter',
            'roles'      => [
                'recruiter-clerk'
            ]
        ],
        'rec-m'=>[
            'slug'       => 'rec-m',
            'grade' => 'manager',
            'team'       => 'rec',
            'department' => 'recruiting',
            'name'       => 'Recruiter supervisor',
            'roles'      => [
                'recruiter-admin'
            ]
        ],
        'job-c'=>[
            'slug'       => 'job-c',
            'grade' => 'clerk',
            'team'       => 'rec',
            'department' => 'jobs',
            'name'       => 'Job placement',
            'roles'      => [
                'recruiter-clerk'
            ]
        ],
        'job-m'=>[
            'slug'       => 'job-m',
            'grade' => 'manager',
            'team'       => 'rec',
            'department' => 'jobs',
            'name'       => 'Job placement supervisor',
            'roles'      => [
                'recruiter-admin'
            ]
        ],

    ],
    'wrappers'=>[
        'hr'=>['hr-m', 'hr-c'],
        'mrk'=>['mrk-m', 'mrk-c'],
        'rec'=>['rec-m', 'rec-c'],
        'job'=>['job-m', 'job-c']
    ],
    'blueprint' => [
        'management' => [
            'title'     => 'management and operations',
            'positions' => [
                'dir' => 'dir',
                'acc' => 'acc',
                'hr'  => ['hr-m', 'hr-c'],


            ]
        ],
        'recruiter'  => [
            'title'     => 'recruiter and job positioning',
            'positions' => [
                'rec' => ['rec-m', 'rec-c'],
                'job' => ['job-m', 'job-c'],

            ],
        ],


    ]
];
