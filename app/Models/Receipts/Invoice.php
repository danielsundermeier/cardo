<?php

namespace App\Models\Receipts;

use App\Models\Receipts\Receipt;
use Parental\HasParent;

class Invoice extends Receipt
{
    use HasParent;

    protected function getBaseRouteAttribute() : string
    {
        return 'invoice';
    }
}
