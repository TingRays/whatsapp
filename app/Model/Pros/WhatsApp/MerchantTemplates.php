<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:08:46
*/

namespace App\Model\Pros\WhatsApp;

use Abnermouke\EasyBuilder\Module\BaseModel;

/**
 * 商户模板表
 * Class MerchantTemplates
 * @package App\Model\Pros\WhatsApp
*/
class MerchantTemplates extends BaseModel
{
    //设置表名
    protected $table = self::TABLE_NAME;

    //定义表链接信息
    protected $connection = 'mysql';

    //定义表名
    public const TABLE_NAME = 'wa_merchant_templates';

    //定义表链接信息
    public const DB_CONNECTION = 'mysql';

    //类型分组解释信息
    public const TYPE_GROUPS = [
        //是否选择
        '__switch__' => [self::SWITCH_ON => '是', self::SWITCH_OFF => '不是'],
        //默认状态
        '__status__' => [self::STATUS_ENABLED => '正常启用', self::STATUS_DISABLED => '禁用中', self::STATUS_VERIFYING => '审核中', self::STATUS_VERIFY_FAILED => '审核失败', self::STATUS_DELETED => '已删除'],

        'languages' => self::LANGUAGES,

    ];

    public const TYPE_OF_TRANSCATION = 1;//交易事务
    public const TYPE_OF_MARKETING = 2;//市场营销
    public const TYPE_OF_TEMPORARY_PASSWORD = 3;//一次性密码

    public const HEADER_OF_NULL = 1;//无页眉
    public const HEADER_OF_TEXT = 2;//文字页眉
    public const HEADER_OF_MEDIA_DOCUMENT = 3;//媒体文档页眉
    public const HEADER_OF_MEDIA_IMAGE = 4;//媒体图片页眉
    public const HEADER_OF_MEDIA_VIDEO = 5;//媒体视频页眉

    public const LANGUAGES = [
        'af'=>['南非荷蘭文','Afrikaans'],
        'sq'=>['阿爾巴尼亞文','Albanian'],
        'ar'=>['阿拉伯文','Arabic'],
        'az'=>['亞塞拜然文','Azerbaijani'],
        'bn'=>['孟加拉文','Bengali'],
        'bg'=>['保加利亞文','Bulgarian'],
        'ca'=>['加泰隆尼亞文','Catalan'],
        'zh_CN'=>['中文（中國）','Chinese (CHN)'],
        'zh_HK'=>['中文（香港）','Chinese (HKG)'],
        'zh_TW'=>['中文（台灣）','Chinese (TAI)'],
        'hr'=>['克羅埃西亞文','Croatian'],
        'cs'=>['捷克文','Czech'],
        'da'=>['丹麥文','Danish'],
        'nl'=>['荷蘭文','Dutch'],
        'en'=>['英文','English'],
        'en_GB'=>['英文（英國）','English (UK)'],
        'en_US'=>['英文（美國）','English (US)'],
        'et'=>['愛沙尼亞文','Estonian'],
        'fil'=>['菲律賓文','Filipino'],
        'fi'=>['芬蘭文','Finnish'],
        'fr'=>['法文','French'],
        'ka'=>['格鲁吉亚语','Georgian'],
        'de'=>['德文','German'],
        'el'=>['希臘文','Greek'],
        'gu'=>['古吉拉特文','Gujarati'],
        'ha'=>['豪沙文','Hausa'],
        'he'=>['希伯來文','Hebrew'],
        'hi'=>['印度文','Hindi'],
        'hu'=>['匈牙利文','Hungarian'],
        'id'=>['印尼文','Indonesian'],
        'ga'=>['愛爾蘭文','Irish'],
        'it'=>['義大利文','Italian'],
        'ja'=>['日文','Japanese'],
        'kn'=>['康納達文','Kannada'],
        'kk'=>['哈薩克文','Kazakh'],
        'rw_RW'=>['卢旺达语','Kinyarwanda'],
        'ky_KG'=>['吉尔吉斯斯坦语','Kyrgyz (Kyrgyzstan)'],
        'ko'=>['韓文','Korean'],
        'lo'=>['寮文','Lao'],
        'lv'=>['拉脫維亞文','Latvian'],
        'lt'=>['立陶宛文','Lithuanian'],
        'mk'=>['馬其頓文','Macedonian'],
        'ms'=>['馬來文','Malay'],
        'ml'=>['馬拉雅拉姆文','Malayalam'],
        'mr'=>['馬拉提文','Marathi'],
        'nb'=>['挪威文','Norwegian'],
        'fa'=>['波斯文','Persian'],
        'pl'=>['波蘭文','Polish'],
        'pt_BR'=>['葡萄牙文（巴西）','Portuguese (BR)'],
        'pt_PT'=>['葡萄牙文（葡萄牙）','Portuguese (POR)'],
        'pa'=>['旁遮普文','Punjabi'],
        'ro'=>['羅馬尼亞文','Romanian'],
        'ru'=>['俄羅斯文','Russian'],
        'sr'=>['塞爾維亞文','Serbian'],
        'sk'=>['斯洛伐克文','Slovak'],
        'sl'=>['斯洛維尼亞文','Slovenian'],
        'es'=>['西班牙文','Spanish'],
        'es_AR'=>['西班牙文（阿根廷）','Spanish (ARG)'],
        'es_ES'=>['西班牙文（西班牙）','Spanish (SPA)'],
        'es_MX'=>['西班牙文（墨西哥）','Spanish (MEX)'],
        'sw'=>['斯瓦西里文','Swahili'],
        'sv'=>['瑞典文','Swedish'],
        'ta'=>['坦米爾文','Tamil'],
        'te'=>['特拉古文','Telugu'],
        'th'=>['泰文','Thai'],
        'tr'=>['土耳其文','Turkish'],
        'uk'=>['烏克蘭文','Ukrainian'],
        'ur'=>['晤魯都文','Urdu'],
        'uz'=>['烏茲別克文','Uzbek'],
        'vi'=>['越南文','Vietnamese'],
        'zu'=>['祖魯文','Zulu'],
    ];
}
