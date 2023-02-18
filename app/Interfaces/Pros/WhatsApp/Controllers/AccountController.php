<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:14:30
*/

namespace App\Interfaces\Pros\WhatsApp\Controllers;

use App\Interfaces\Pros\WhatsApp\Services\AccountInterfaceService;
use App\Repository\Pros\WhatsApp\AccountTagRepository;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * 用户基础控制器
 * Class AccountController
 * @package App\Interfaces\Pros\WhatsApp\Controllers
 */
class AccountController extends BaseController
{

    /**
     * 用户页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //渲染页面
        return view('pros.whatsapp.account.index');
    }

    /**
     * 用户账户列表
     * @param Request $request
     * @param AccountInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists(Request $request, AccountInterfaceService $service){
        //获取（BM）账户列表
        $service->lists($request);
        //响应接口
        return responseService($service);
    }

    /**
     * 获取用户详情
     * @param $id
     * @param Request $request
     * @param AccountInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function detail($id, Request $request, AccountInterfaceService $service){
        //获取用户详情
        $service->detail($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 保存用户信息
     * @param $id
     * @param Request $request
     * @param AccountInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store($id, Request $request, AccountInterfaceService $service){
        //保存（BM）账户信息
        $service->store($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 更改用户状态
     * @param $id
     * @param Request $request
     * @param AccountInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function enable($id, Request $request, AccountInterfaceService $service){
        //更改用户状态
        $service->enable($id, $request);
        //响应接口
        return responseService($service);
    }

    /**
     * 用户导入管理
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function posts(){
        $groups = (new AccountTagRepository())->get([],['id','guard_name']);
        //快递单管理
        return view('pros.whatsapp.account.posts',compact('groups'));
    }

    /**
     * 用户导入方法
     * @param Request $request
     * @param AccountInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function import(Request $request, AccountInterfaceService $service){
        //导入发货单
        $service->import($request);
        //导入快递单
        return responseService($service);
    }

    /**
     * txt文本 - 用户导入管理
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function postTxt(){
        $groups = (new AccountTagRepository())->get([],['id','guard_name']);
        //快递单管理
        return view('pros.whatsapp.account.posts_txt',compact('groups'));
    }

    /**
     * txt文本 - 用户导入方法
     * @param Request $request
     * @param AccountInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function importTxt(Request $request, AccountInterfaceService $service){
        //导入发货单
        $service->importTxt($request);
        //导入快递单
        return responseService($service);
    }
}
