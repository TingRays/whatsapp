<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-03-15
 * Time: 02:43:04
*/

namespace App\Services\Pros\Console;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Currency\StorageFileLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use App\Services\Pros\System\TemporaryFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

/**
 * 上传逻辑服务容器
 * Class UploadService
 * @package App\Services\Abnermouke\Console
*/
class UploadService extends BaseService
{

    /**
    * 引入父级构造
    * UploadService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 上传文件
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request Request
     * @return array|bool
     * @throws \Exception
     */
    public function upload($request)
    {
        //初始化storage储存信息
        $storage_dictionary = $request->get('dictionary', 'pros/builder/uploader/files');
        $storage_disk = 'public';
        //处理目录
        StorageFileLibrary::check((!empty($storage_dictionary) ? $storage_dictionary.'/' : '').'.DS_Store', $storage_disk, false);
        StorageFileLibrary::check('temps/.DS_Store', $storage_disk, false);
        //根据文件类型处理
        switch ($file_type = $request->get('file_type', 'source')) {
            case 'image_base64':
                //获取base64
                $base64 = $request->get('file');
                //判断文件信息
                if (!$base64 || empty($base64)) {
                    //返回错误
                    return $this->fail(CodeLibrary::DATA_MISSING, '缺少文件信息');
                }
                //获取文件元名称
                $origin_name = $request->get('origin_name', '');
                //生成随机名称
                $storage_name = 'temps/'.md5($origin_name.Uuid::uuid4()->toString().Str::random(6));
                //实例化图片
                $imageHandler = Image::make($base64);
                //保存文件
                $imageHandler->save(Storage::disk($storage_disk)->path($storage_name));
                //释放内存
                unset($imageHandler);
                break;
            default:
                //获取文件信息
                $file = $request->file('file');
                //判断文件有效性
                if (!$file->isValid()) {
                    //返回错误
                    return $this->fail(CodeLibrary::FILE_RESOURCE_UNDEFINED, '文件不可用');
                }
                //获取文件元名称
                $origin_name = $request->get('origin_name', $file->getClientOriginalName());
                //生成随机名称
                $storage_name = md5($origin_name.Uuid::uuid4()->toString().Str::random(6));
                //保存文件
                if (!$file->storeAs('temps', $storage_name, $storage_disk)) {
                    //返回失败
                    return $this->fail(CodeLibrary::FILE_MOVE_FAILED, '文件保存失败');
                }
                //修改名称
                $storage_name = 'temps/'.$storage_name;
                break;
        }
        //保存文件闭关返回结果
        return  $this->storeFile($storage_dictionary, $storage_name, $origin_name, $storage_disk);
    }

    /**
     * 保存文件
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $storage_dictionary
     * @param $storage_name
     * @param string $origin_name
     * @param string $storage_disk
     * @return array|bool
     * @throws \Exception
     */
    private function storeFile($storage_dictionary, $storage_name, $origin_name = '', $storage_disk = 'public')
    {
        //获取文件后缀
        $extension = strtolower(data_get(pathinfo($origin_name), 'extension', ''));
        //获取hash值
        $file_hash = hash_file('md5', ($storage_path = Storage::disk($storage_disk)->path($storage_name)));
        //更改原文件名
        $storage_name = (!empty($storage_dictionary) ? $storage_dictionary.'/' : '').($origin_name ? pathinfo($origin_name)['filename'].'_' : '').md5($origin_name.$file_hash);
        //修复空格被转义问题
        $storage_name = str_replace([' ', '.', ',', '-', '[', ']', '(', ')'], ['_', '_', '_', '_', '_', '_', '_', '_'], $storage_name).($extension ? '.'.$extension : '');
        //强制覆盖文件
        if (!StorageFileLibrary::move($storage_path, $storage_name, $storage_disk, true)) {
            //返回失败
            return $this->fail(CodeLibrary::FILE_MOVE_FAILED, '文件创建失败');
        }
        //创建临时文件
        if (!($service = new TemporaryFileService())->temporary($storage_name, $storage_disk)) {
            //返回失败
            return $this->fail(CodeLibrary::FILE_CAN_NOT_BE_WRITE, '文件信息创建失败');
        }
        //整理信息
        $temporary = $service->getResult();
        //设置结果
        $result = Arr::only(data_get($temporary, 'file', []), ['storage_disk', 'storage_name', 'link', 'expire_time', 'file_info']);
        //返回公共
        return $this->success($result);
    }

    /**
     * 百度编辑器资源上传
     * @Author Abnermouke <abnermouke@gmail.com>
     * @Originate in Company <Macbook Pro>
     * @Time 2022-10-25 11:59:15
     * @param $request Request
     * @return array|bool
     * @throws \Exception
     */
    public function uploadUeditor($request)
    {
        //获取上传配置
        $config = config('pros.ueditor_upload');
        //根据操作类型处理
        switch ($action = $request->get('action')) {
            //图片上传
            case 'uploadimage':
                $upConfig = array(
                    "pathFormat" => $config['imagePathFormat'],
                    "maxSize" => $config['imageMaxSize'],
                    "allowFiles" => $config['imageAllowFiles'],
                    'fieldName' => $config['imageFieldName'],
                );
                break;
            //媒体资源上传
            case 'uploadmedia':
                $upConfig = array(
                    "pathFormat" => $config['videoPathFormat'],
                    "maxSize" => $config['videoMaxSize'],
                    "allowFiles" => $config['videoAllowFiles'],
                    'fieldName' => $config['videoFieldName'],
                );
                break;
            //文件上传
            case 'uploadfile':
                $upConfig = array(
                    "pathFormat" => $config['filePathFormat'],
                    "maxSize" => $config['fileMaxSize'],
                    "allowFiles" => $config['fileAllowFiles'],
                    'fieldName' => $config['fileFieldName'],
                );
                break;
            default:
                //返回配置信息
                return $this->success($config);
                break;
        }
        //上传文件
        $file = $this->ueditorUpload($upConfig, $request);
        //初始化返回信息
        $result = ["state" => $this->getMessage(), "url" => '', "title" => '', "original" => '', "type" => '', "size" => ''];
        //上传文件信息
        if ($file) {
            //整理结果集
            $result = ['state' => 'SUCCESS', 'url' => $file['link'], 'title' => $file['name'], 'original' => $file['origin_name'], 'type' => $file['extension'], 'size' => $file['size']];
        }
        //返回处理对象
        return $this->success($result);
    }

    /**
     * Ueditor文件上传
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $config
     * @param $request
     * @param string $storage_disk
     * @return array|bool
     * @throws \Exception
     */
    private function ueditorUpload($config, $request, $storage_disk = 'public')
    {
        //获取文件信息
        $file = $request->file($config['fieldName']);
        //判断文件有效性
        if (!$file->isValid()) {
            //返回失败
            return $this->fail(CodeLibrary::FILE_RESOURCE_UNDEFINED, '文件无效');
        }
        //判断文件大小
        if ($file_size = $file->getSize() > (int)$config['maxSize']) {
            //返回失败
            return $this->fail(CodeLibrary::FILE_WITHOUT_PERMISSION, '文件最大：'.friendly_file_size($config['maxSize']));
        }
        //获取文件后缀
        $extension = strtolower(data_get(pathinfo($file->getClientOriginalName()), 'extension', ''));
        //判断文件格式
        if ($extension && !in_array(('.'.$extension), $config['allowFiles'], true)) {
            //返回失败
            return $this->fail(CodeLibrary::FILE_WITHOUT_PERMISSION, '文件格式有误');
        }
        //获取文件元名称
        $origin_name = $request->get('origin_name', $file->getClientOriginalName());
        //生成随机名称
        $storage_name = md5($origin_name.Uuid::uuid4()->toString().Str::random(6));
        //保存文件
        if (!$file->storeAs('temps', $storage_name, $storage_disk)) {
            //返回失败
            return $this->fail(CodeLibrary::FILE_MOVE_FAILED, '文件保存失败');
        }
        //修改名称
        $storage_name = 'temps/'.$storage_name;
        //保存文件
        if (!$this->storeFile($config['pathFormat'], $storage_name, $origin_name, $storage_disk)) {
            //返回信息
            return false;
        }
        //获取结果
        $file = $this->getResult();
        //获取结果集
        return ['link' => $file['link'], 'name' => $file['file_info']['basename'], 'origin_name' => $origin_name, 'extension' => $extension, 'size' => $file_size];
    }
}
