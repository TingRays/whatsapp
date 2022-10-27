<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 16:37:32
*/

namespace App\Services\Pros\System;

use Abnermouke\EasyBuilder\Library\Currency\StorageFileLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use App\Handler\Cache\Data\Pros\System\ConfigCacheHandler;
use App\Model\Pros\System\Configs;
use App\Repository\Pros\System\TemporaryFileRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

/**
 * 临时文件记录逻辑服务容器
 * Class TemporaryFileService
 * @package App\Services\Pros\System
*/
class TemporaryFileService extends BaseService
{

    /**
    * 引入父级构造
    * TemporaryFileService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 设置临时文件可用
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $links string|array 文件地址
     * @param string $storage_disk  储存驱动
     * @param bool $clear_local 清除本地文件
     * @param mixed $return 返回类型 array 数组   string 字符串
     * @return array|bool
     * @throws \Exception
     */
    public function enable($links, $storage_disk = 'public', $clear_local = true, $return = false)
    {
        //获取类型
        $return = $return ? $return : (is_array($links) ? 'array' : 'string');
        //整理信息
        if ($links) {
            //处理链接
            $links = is_string($links) ? explode(',', $links) : $links;
            //循环链接
            foreach ($links as $k => $link) {
                //获取地址
                $storage_name = str_replace(($storage_base_path = Storage::disk($storage_disk)->url('')), '', $link);
                //判断文件是否存在
                if (Storage::disk($storage_disk)->exists($storage_name)) {
                    //移除临时文件（不再删除源文件）
                    (new TemporaryFileRepository())->delete(compact('storage_disk', 'storage_name'));
                    //判断是否自动上传七牛云
                    if ((int)(new ConfigCacheHandler())->get('QINIU_SYNC_AUTO', Configs::SWITCH_OFF) === Configs::SWITCH_ON) {
                        //自动上传七牛
                        if ($qiniu_link = StorageFileLibrary::sync_to_qiniu($storage_name, $storage_disk, (new ConfigCacheHandler())->get('QINIU_PARAMS', false), false)) {
                            //替换链接
                            $links[$k] = $qiniu_link;
                        }
                    }
                } else {
                    //判断是否为本地地址（非本站地址不处理）
                    if ($clear_local && strstr($link, $storage_base_path)) {
                        //删除当前文件
                        unset($links[$k]);
                    }
                }
            }
        }
        //返回成功
        return $this->success($return === 'array' ? $links : ($links ? implode(',', $links) : $links));
    }

    /**
     * 创建临时文件信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $storage_name string 文件路径
     * @param string $storage_disk 驱动
     * @param int $expire_second 过期秒数
     * @return array|bool
     * @throws \Exception
     */
    public function temporary($storage_name, $storage_disk = 'public', $expire_second = 0)
    {
        //初始化过期秒数
        $expire_second = (int)$expire_second > 0 ? (int)$expire_second : (int)(new ConfigCacheHandler())->get('TEMPORARY_FILES_AUTO_DELETE_SECOND_LIMIT', 3600);
        //整理信息
        $file = [
            'storage_disk' => $storage_disk,
            'storage_name' => $storage_name,
            'storage_path' => Storage::disk($storage_disk)->path($storage_name),
            'link' => Storage::disk($storage_disk)->url($storage_name),
            'expire_time' => time() + $expire_second,
            'created_at' => auto_datetime(),
            'updated_at' => auto_datetime()
        ];
        //判断信息是否存在
        if (!$temporary_file_id = (new TemporaryFileRepository())->find(Arr::only($file, ['storage_disk', 'storage_name']), 'id')) {
            //创建临时文件信息
            $temporary_file_id = (new TemporaryFileRepository())->insertGetId($file);
        }
        //拆分文件信息
        $file['file_info'] = pathinfo($file['storage_name']);
        //返回成功
        return $this->success(compact('temporary_file_id', 'file'));
    }
}
