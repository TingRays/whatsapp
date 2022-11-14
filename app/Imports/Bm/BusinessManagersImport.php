<?php

namespace App\Imports\Bm;

use Maatwebsite\Excel\Concerns\ToArray;

/**
 * 用户导入
 */
class BusinessManagersImport implements ToArray
{
    public function array(array $array)
    {
        return $array;
    }
}
