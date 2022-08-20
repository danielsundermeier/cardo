<?php

namespace App\Models\Receipts;

use App\Models\Receipts\Receipt;
use Parental\HasParent;

class Invoice extends Receipt
{
    use HasParent;

    const DUE_IN_DAYS = 14;

    protected function getBaseRouteAttribute() : string
    {
        return 'invoice';
    }
}
