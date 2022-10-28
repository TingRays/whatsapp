<?php

namespace App\Imports\Accounts;

use Maatwebsite\Excel\Concerns\ToArray;

/**
 * 用户导入
 */
class AccountsImport implements ToArray
{
    public function array(array $array)
    {
        return $array;
    }
}
