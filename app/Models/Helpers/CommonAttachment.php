<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 20 Dec 2021 13:14:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use JetBrains\PhpStorm\Pure;
use App\Models\Traits;


/**
 * @mixin IdeHelperCommonAttachment
 */
class CommonAttachment extends Model {
    use SoftDeletes;
    use Traits\HasFile;


    protected $connection = 'media';

    protected $guarded = [];


    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    /** @noinspection PhpUnused */
    #[Pure] public function getFormattedFilesizeAttribute(): string
    {
        return $this->formatSizeUnits($this->filesize);

    }






}
