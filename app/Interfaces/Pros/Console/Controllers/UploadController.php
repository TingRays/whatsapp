<?php

namespace App\Interfaces\Pros\Console\Controllers;

use Abnermouke\EasyBuilder\Module\BaseController;
use App\Services\Pros\Console\UploadService;
use Illuminate\Http\Request;

/**
 * 上传基础控制器
 * Class UploadController
 * @package App\Http\Controllers\Pros\Console
 */
class UploadController extends BaseController
{

    /**
     * 上传文件
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-07-24 17:41:56
     * @param Request $request
     * @param UploadService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function upload(Request $request, UploadService $service)
    {
        //上传文件
        $service->upload($request);
        //响应数据
        return responseService($service);
    }

    /**
     * 百度编辑器资源上传
     * @Author Abnermouke <abnermouke@gmail.com>
     * @Originate in Company <Macbook Pro>
     * @Time 2022-07-24 17:41:56
     * @param Request $request
     * @param UploadService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ueditor(Request $request, UploadService $service)
    {
        //上传文件信息
        $service->uploadUeditor($request);
        //返回上传结果
        return response()->json($service->getResult(), 200, [], JSON_UNESCAPED_UNICODE);
    }


}
