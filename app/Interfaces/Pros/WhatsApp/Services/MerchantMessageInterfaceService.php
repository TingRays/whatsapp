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
use Abnermouke\Pros\Builders\BuilderProvider;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Model\Pros\WhatsApp\Accounts;
use App\Model\Pros\WhatsApp\MerchantMessages;
use App\Model\Pros\WhatsApp\MerchantMessagesLogs;
use App\Model\Pros\WhatsApp\MerchantTemplates;
use App\Repository\Pros\WhatsApp\AccountRepository;
use App\Repository\Pros\WhatsApp\AccountTagRepository;
use App\Repository\Pros\WhatsApp\MerchantMessageRepository;
use App\Repository\Pros\WhatsApp\MerchantMessagesLogRepository;
use App\Repository\Pros\WhatsApp\MerchantRepository;
use App\Repository\Pros\WhatsApp\MerchantTemplateRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        //$conditions = ['mode' => ['!=', MerchantMessages::MODE_OF_TIMING]];
        //判断是否筛选状态
        if ($request->exists('mode')) {
            //设置默认条件
            $conditions['mode'] = (int)$request->get('mode', MerchantMessages::MODE_OF_TIMING);
        }
        //查询列表
        $lists = (new MerchantMessageRepository())->lists($conditions, [], [], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        if ($lists && !empty($lists['lists'])) {
            //获取模板
            $template_ids = array_column($lists['lists'],'template_id');
            $templates = (new MerchantTemplateRepository())->get(['id'=>['in',$template_ids]],['id','title']);
            $templates = array_column($templates, null, 'id');
            //循环列表
            foreach ($lists['lists'] as $k => $list) {
                //设置信息
                $lists['lists'][$k]['template_title'] = $templates[$list['template_id']]['title']??'-';
                $lists['lists'][$k]['type_str'] = MerchantMessages::TYPE_GROUPS['type'][$list['type']]??'-';
                $lists['lists'][$k]['mode_str'] = MerchantMessages::TYPE_GROUPS['mode'][$list['mode']]??'-';
                $lists['lists'][$k]['timing_send_time'] = auto_datetime('Y-m-d H:i',$list['timing_send_time']);
            }
        }
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

    /**
     * 消息群发用户列表
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function accounts($id, $request){
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        $lists = (new MerchantMessagesLogRepository())->lists(['merchant_messages_id'=>$id,'mode'=>MerchantMessagesLogs::MODE_OF_MERCHANT],['merchant_id','account_id','type','status','updated_at'],[], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        //获取商户
        $merchant_ids = array_column($lists['lists'],'merchant_id');
        $merchants = (new MerchantRepository())->get(['id'=>['in',$merchant_ids]],['id','guard_name']);
        $merchants = array_column($merchants, null, 'id');
        //获取用户
        $account_ids = array_column($lists['lists'],'account_id');
        $accounts = (new AccountRepository())->get(['id'=>['in',$account_ids]],['id','global_roaming','mobile']);
        $accounts = array_column($accounts, null, 'id');
        //循环列表
        foreach ($lists['lists'] as $k => $list) {
            //判断是否已读
            $lists['lists'][$k]['merchant_name'] = $merchants[$list['merchant_id']]['guard_name']??'无';
            $lists['lists'][$k]['account_mobile'] = !empty($accounts[$list['account_id']]) ? '+('.$accounts[$list['account_id']]['global_roaming'].')-'.$accounts[$list['account_id']]['mobile'] : '-';
            $lists['lists'][$k]['type_str'] = MerchantMessagesLogs::TYPE_GROUPS['type'][$list['type']]??'-';
            $lists['lists'][$k]['status_str'] = MerchantMessagesLogs::TYPE_GROUPS['__status__'][$list['status']]??'-';
        }
        //初始化表格
        $render = TableBuilder::CONTENT()
            ->setItems(function (\Abnermouke\Pros\Builders\Table\Tools\TableItemBuilder $builder) {
                //其他字段
                $builder->info('merchant_name', '商户')->description('ID：{merchant_id}');
                $builder->string('account_mobile', '用户电话')->badge('primary');
                $builder->string('type_str', '发送类型')->badge('success');
                $builder->string('status_str', '状态');
                $builder->string('updated_at', '发送时间')->date('Y-m-d H:i:s');
                //$builder->option('is_read', '是否已读')->options(AccountsMessages::TYPE_GROUPS['readable'], BuilderProvider::THEME_COLORS['switch']);
            })
            ->target(Str::random(10), 5, '', '')
            ->setLists($lists)
            ->render(true);
        //返回表格内容
        return $this->success(['html' => $render]);
    }
}
