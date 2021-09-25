<?php

namespace App\Actions\Selling\Shop;

use App\Models\Selling\Shop;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreShop
{
    use AsAction;

    public function handle($shopData): Model|Shop
    {
        return Shop::create($shopData);
    }


}
