<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 23:58:09
*/

namespace App\Interfaces\Pros\Console\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Model\Pros\System\SmsLogs;
use App\Repository\Pros\System\SmsLogRepository;

/**
 * 短信记录接口逻辑服务容器
 * Class SmsLogService
 * @package App\Interfaces\Pros\Console\Services
*/
class SmsLogInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * SmsLogInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 获取短信记录列表
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request
     * @return array|bool
     * @throws \Exception
     */
    public function lists($request)
    {
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //整理查询条件
        $conditions = ['status' => ['!=', SmsLogs::STATUS_VERIFYING]];
        //判断是否筛选状态
        if ($request->exists('status')) {
            //设置默认条件
            $conditions['status'] = (int)$request->get('status', SmsLogs::STATUS_ENABLED);
        }
        //判断筛选条件
        if ($filters = data_get($data, 'filters', [])) {
            //循环筛选条件
            foreach ($filters as $filter => $value) {
                //根据筛选项设置条件
                switch ($filter) {
                    case 'keyword':
                        $value && $conditions[implode('|', ['gateway', 'mobile', 'sign_name', 'template_id', 'content'])] = ['like', '%'.$value.'%'];
                        break;
                    case 'created_at':
                        $value && $conditions['created_at'] = ['between', $value];
                        break;
                    case 'updated_at':
                        $value && $conditions['updated_at'] = ['between', $value];
                        break;
                }
            }
        }
        //查询列表
        $lists = (new SmsLogRepository())->lists($conditions, [], [], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }
}
