<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-03
 * Time: 17:26:52
*/

namespace App\Interfaces\Pros\WhatsApp\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Form\FormBuilder;
use Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Model\Pros\WhatsApp\Fictitious;
use App\Model\Pros\WhatsApp\TaskQueues;
use App\Repository\Pros\WhatsApp\FictitiouRepository;
use App\Repository\Pros\WhatsApp\TaskQueueRepository;
use Illuminate\Support\Arr;

/**
 * 虚拟手机号接口逻辑服务容器
 * Class FictitiouService
 * @package App\Interfaces\Pros\WhatsApp\Services
*/
class FictitiouInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * FictitiouInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 虚拟手机号列表
     * @param $request
     * @return array|bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function lists($request){
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //整理查询条件
        $conditions = [];
        //判断是否筛选状态
        if ($request->exists('status')) {
            //设置默认条件
            $conditions['status'] = (int)$request->get('status', Fictitious::STATUS_ENABLED);
        }
        //判断筛选条件
        if ($filters = data_get($data, 'filters', [])) {
            //循环筛选条件
            foreach ($filters as $filter => $value) {
                //根据筛选项设置条件
                switch ($filter) {
                    case 'keyword':
                        $value && $conditions[implode('|', ['id', 'mobile'])] = ['like', '%'.$value.'%'];
                        break;
                    case 'global_roaming':
                        $value && $conditions['global_roaming'] = $value;
                        break;
                    case 'updated_at':
                        $value && $conditions['updated_at'] = ['date', $value];
                        break;
                }
            }
        }
        //查询列表
        $lists = (new FictitiouRepository())->lists($conditions, ['*'], [], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        foreach ($lists['lists'] as &$list){
            $list['global_roaming'] = '+'.$list['global_roaming'];
        }
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }

    public function detail($request){
        $region_code = [];
        foreach (Fictitious::REGION_CODE as $k=>$item){
            $region_code[$k] = $item[0].'（+'.$item[1].'）'.$item[2];
        }
        //渲染表单内容
        $render = FormBuilder::make()
            ->setSubmit(route('whatsapp.console.fictitious.store'))
            ->setItems(function (FormItemBuilder $builder) use ($region_code) {
                $builder->select('global_roaming', '国际区号')->options($region_code,'AF')->required();
                $builder->input('number_segment', '号码段')->description('手机号号段')->required();
                $builder->input('count', '生成总数')->description('生成号码的总数量')->required();
            })
            ->setData([])
            ->render();
        //返回成功
        return $this->success(['html' => $render]);
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
        //获取更改项
        $info = Arr::only($data['__data__'], $data['__edited__']);

        $count = $info['count'];
        $region_code = Fictitious::REGION_CODE[$info['global_roaming']];
        $len_arr[] = $region_code[3];
        if (isset($region_code[4])){
            $len_arr[] = $region_code[4];
        }
        $global_roaming = $region_code[1];
        //添加信息
        $param['created_at'] = auto_datetime();
        $param['updated_at'] = auto_datetime();
        $default_num = 300;
        if ($count > $default_num){
            for ($i=0;$i<$default_num;$i++){
                $len_key = array_rand($len_arr);
                $len = $len_arr[$len_key] - strlen($info['number_segment']);
                $mobile_segment = get_random($len);
                $mobile = $info['number_segment'].$mobile_segment;
                //判断信息是否存在
                if ((new FictitiouRepository())->exists(['global_roaming' => $global_roaming,'mobile'=>$mobile])) {
                    continue;
                }
                $param['global_roaming'] = $global_roaming;
                $param['mobile'] = $mobile;
                $param['status'] = Fictitious::STATUS_VERIFYING;
                (new FictitiouRepository())->insertGetId($param);
            }
        }
        $info['count'] = $info['count'] - $default_num;
        (new TaskQueueRepository())->insertGetId(['type'=>TaskQueues::TYPE_OF_FICTITIOUS,'source'=>0,'params'=>$info,'created_at'=>auto_datetime(),'updated_at'=>auto_datetime()]);
        //返回成功
        return $this->success();
    }
}
