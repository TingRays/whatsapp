<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-28
 * Time: 08:52:50
*/

namespace App\Interfaces\Pros\WhatsApp\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Model\Pros\WhatsApp\Accounts;
use App\Model\Pros\WhatsApp\MerchantMessages;
use App\Model\Pros\WhatsApp\MerchantMessagesLogs;
use App\Model\Pros\WhatsApp\MerchantTemplates;
use App\Repository\Pros\WhatsApp\AccountRepository;
use App\Repository\Pros\WhatsApp\AccountTagRepository;
use App\Repository\Pros\WhatsApp\MerchantMessageRepository;
use App\Repository\Pros\WhatsApp\MerchantTemplateRepository;
use Illuminate\Http\Request;

/**
 * 商户发送消息接口逻辑服务容器
 * Class MerchantMessageService
 * @package App\Interfaces\Pros\WhatsApp\Services
*/
class MerchantMessageInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * MerchantMessageInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 商户发送消息列表
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
                        $value && $conditions[implode('|', ['id', 'title'])] = ['like', '%'.$value.'%'];
                        break;
                    case 'created_at':
                        $value && $conditions['created_at'] = ['date', $value];
                        break;
                    case 'updated_at':
                        $value && $conditions['updated_at'] = ['date', $value];
                        break;
                }
            }
        }
        //整理查询条件
        $conditions = ['mode' => ['!=', MerchantMessages::MODE_OF_TIMING]];
        //判断是否筛选状态
        if ($request->exists('mode')) {
            //设置默认条件
            $conditions['mode'] = (int)$request->get('mode', MerchantMessages::MODE_OF_TIMING);
        }
        //查询列表
        $lists = (new MerchantMessageRepository())->lists($conditions, [], [], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }

    /**
     * 保存消息发送
     * @param Request $request
     * @return array|bool
     * @throws \Exception
     */
    public function detail(Request $request)
    {
        //查询全部有效用户
        $accounts = (new AccountRepository())->get(['status' => Accounts::STATUS_ENABLED],
            ['id', 'global_roaming', 'mobile'], [], ['updated_at' => 'desc']);
        //初始化信息
        $accounts = array_column($accounts, null, 'id');
        //循环用户信息
        foreach ($accounts as $k => $account) {
            //设置信息
            $accounts[$k] = '+('.$account['global_roaming'].')-'.$account['mobile'];
        }
        //查询全部用户标签
        $account_tags = (new AccountTagRepository())->get([], ['id', 'guard_name', 'description']);
        //初始化信息
        $account_tags = array_column($account_tags, null, 'id');
        //循环用户标签信息
        foreach ($account_tags as $k => $tag) {
            //设置信息
            $account_tags[$k] = $tag['guard_name'].'（'.$tag['description'].'）';
        }
        $templates = (new MerchantTemplateRepository())->get(['status'=>MerchantTemplates::STATUS_ENABLED],
            ['id', 'title', 'language'], [], ['updated_at' => 'desc']);
        //初始化信息
        $templates = array_column($templates, null, 'id');
        //循环模板信息
        foreach ($templates as $k => $template) {
            //设置信息
            $templates[$k] = $template['title'].'（'.MerchantTemplates::TYPE_GROUPS['languages'][$template['language']][0].'）';
        }
        $default_title = '默认标题'.rand(100,999).auto_datetime('YmdHi');
        //返回数据
        return $this->success(compact('accounts', 'account_tags', 'templates', 'default_title'));
    }

    public function store($request){
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //判断更改项
        if (!($edited = $data['__edited__'])) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '信息无更新');
        }
        //发送用户ID
        $account_ids = [];
        $type = 0;
        //整理指定用户信息
        switch ($method = data_get($data, '__data__.method', 'single'))
        {
            case 'single':
                //设置用户ID
                if ($account_id = data_get($data, '__data__.account_id', 0)) {
                    //设置用户ID
                    $account_ids = [(int)$account_id];
                }
                $type = MerchantMessages::TYPE_OF_SINGLE;
                break;
            case 'group':
                //设置用户ID
                $account_ids = data_get($data, '__data__.account_ids', []);
                $type = MerchantMessages::TYPE_OF_GROUP;
                break;
            case 'tags':
                //获取标签
                if ($tag_id = data_get($data, '__data__.account_tag_id', 0)) {
                    //查询所有当前标签用户
                    $account_ids = object_2_array((new AccountRepository())->pluck('id', ['status' => Accounts::STATUS_ENABLED, 'tag_ids' => ['json-contains', (int)$tag_id]]));
                }
                $type = MerchantMessages::TYPE_OF_TAGS;
                break;
        }
        //判断用户信息
        if (!$account_ids) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '暂无满足条件的用户');
        }
        $timing_send_time = 0;
        if ($data['__data__']['mode'] == MerchantMessages::MODE_OF_TIMING){
            $timing_send_time = to_time($data['__data__']['timing_send_time']);
        }
        $template_id = $data['__data__']['template_id'];
        $template_info = (new MerchantTemplateRepository())->row(['id'=>$template_id]);

        $messages = [
            'title' => $data['__data__']['title'],
            'type' => $type,
            'mode' => $data['__data__']['mode'],
            'template_id' => $template_id,
            'content' => $template_info['body'],
            'timing_send_time' => $timing_send_time,
            'status' => MerchantMessages::STATUS_DISABLED,
            'created_at' => auto_datetime(),
            'updated_at' => auto_datetime()
        ];
        $id = (new MerchantMessageRepository())->insertGetId($messages);

        $service = new MerchantMessagesLogInterfaceService();
        foreach ($account_ids as $account_id){
            //发布消息
            if (!$service->addSendMessage($id, $account_id, MerchantMessagesLogs::TYPE_OF_TEMPLATE, MerchantMessagesLogs::MODE_OF_MERCHANT, $template_id,[],[],MerchantMessagesLogs::STATUS_DISABLED)) {
                //返回失败
                return $this->fail($service->getCode(), $service->getMessage(), $service->getExtra());
            }
        }
        //返回成功
        return $this->success($service->getResult());
    }
}
