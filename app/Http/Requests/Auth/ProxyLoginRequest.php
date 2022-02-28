<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 28 Feb 2022 21:36:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Requests\Auth;



/**
 * @property mixed $token
 */
class ProxyLoginRequest extends LoginRequest
{


    protected function prepareForValidation()
    {

        $data=json_decode(decrypt($this->token) ,true);
        if(is_array($data)){
            $this->merge($data);
        }
    }


}
