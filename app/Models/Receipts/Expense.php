<?php

namespace App\Models\Receipts;

use App\Files\UserFile;
use App\Models\Receipts\Receipt;
use Parental\HasParent;

class Expense extends Receipt
{
    use HasParent;

    protected function getBaseRouteAttribute() : string
    {
        return 'expense';
    }

    public function setName()
    {

    }

    protected function setTextAbove()
    {
        $this->text_above = '';
    }

    protected function setTextBelow()
    {
        $this->text_below = '';
    }

    public function previewFile()
    {
        return $this->morphOne(UserFile::class, 'fileable');
    }
}
