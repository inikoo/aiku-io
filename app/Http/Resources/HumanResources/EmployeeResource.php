<?php

namespace App\Http\Resources\HumanResources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @property int $id
 * @property \App\Models\Helpers\Contact $contact
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property string $state
 * @property string $nickname
 * @property string $worker_number
 * @property mixed $employment_start_at
 * @property mixed $employment_end_at
 * @property \App\Models\System\User $user
 */
class EmployeeResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'id'                  => $this->id,
            'contact'             => $this->contact->only('name', 'email', 'phone'),
            'nickname'            => $this->nickname,
            'worker_number'       => $this->worker_number,
            'state'               => $this->state,
            'employment_start_at' => $this->employment_start_at,
            'employment_end_at'   => $this->employment_end_at,
            'user'                => $this->user?->only('username', 'status'),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
