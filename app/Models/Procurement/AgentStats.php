<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 02 Feb 2022 02:47:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Procurement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;



/**
 * @mixin IdeHelperAgentStats
 */
class AgentStats extends Model
{

    use UsesTenantConnection;

    protected $table = 'agent_stats';

    protected $guarded = [];


    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }


}
