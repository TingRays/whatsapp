<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 20:00:39
*/

namespace App\Interfaces\Pros\Console\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Model\Pros\Console\AdminLogs;
use App\Model\Pros\Console\Admins;
use App\Repository\Pros\Console\AdminLogRepository;

/**
 * 管理员操作记录接口逻辑服务容器
 * Class AdminLogService
 * @package App\Interfaces\Pros\Console\Services
*/
class AdminLogInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * AdminLogInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 获取列表
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request
     * @return array|bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function lists($request)
    {
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //整理查询条件
        $conditions = [];
        //判断筛选条件
        if ($filters = data_get($data, 'filters', [])) {
            //循环筛选条件
            foreach ($filters as $filter => $value) {
                //根据筛选项设置条件
                switch ($filter) {
                    case 'keyword':
                        $value && $conditions[implode('|', [Admins::TABLE_NAME.'.nickname', AdminLogs::TABLE_NAME.'.content', Admins::TABLE_NAME.'.username'])] = ['like', '%'.$value.'%'];
                        break;
                    case 'admin_id':
                        (int)$value > 0 && $conditions[AdminLogs::TABLE_NAME.'.admin_id'] = (int)$value;
                        break;
                    case 'created_at':
                        $value && $conditions[AdminLogs::TABLE_NAME.'.created_at'] = ['between', $value];
                        break;
                }
            }
        }
        //查询列表
        $lists = (new AdminLogRepository())->lists($conditions, [AdminLogs::TABLE_NAME.'.*', Admins::TABLE_NAME.'.nickname', Admins::TABLE_NAME.'.avatar', Admins::TABLE_NAME.'.mobile', Admins::TABLE_NAME.'.email'], [
            ['left', Admins::TABLE_NAME, AdminLogs::TABLE_NAME.'.admin_id', '=', Admins::TABLE_NAME.'.id']
        ], data_get($data, 'sorts', [AdminLogs::TABLE_NAME.'.id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }
}
