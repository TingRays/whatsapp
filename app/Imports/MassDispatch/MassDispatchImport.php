<?php

namespace App\Imports\MassDispatch;

use Maatwebsite\Excel\Concerns\ToArray;

/**
 * 页面群发手机号导入
 */
class MassDispatchImport implements ToArray
{
    public function array(array $array)
    {
        return $array;
    }
}
