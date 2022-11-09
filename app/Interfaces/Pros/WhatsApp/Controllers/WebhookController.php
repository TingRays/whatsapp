<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-29
 * Time: 10:05:34
*/

namespace App\Interfaces\Pros\WhatsApp\Controllers;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use App\Interfaces\Pros\WhatsApp\Services\WebhookInterfaceService;
use Illuminate\Http\Request;
use Abnermouke\EasyBuilder\Module\BaseController;

/**
 * WebHooks基础控制器
 * Class WebhookController
 * @package App\Interfaces\Pros\WhatsApp\Controllers
 */
class WebhookController extends BaseController
{

    /**
     * WebHooks页面
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-29 10:05:34
     * @param Request $request
     * @param WebhookInterfaceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
    */
    public function index(Request $request, WebhookInterfaceService $service)
    {
        //回调地址：https://www.whatsqunfa.com/whatsapp/console/webhook
        $service->webhook($request);
        if ($service->getState()){
            return $request->get('hub_challenge','');
        }
        //响应接口
        //return responseService($service);
        return CodeLibrary::MISSING_PERMISSION;
    }

    public function termsPrivacy(){
        $body = '我们会遵循正当、合法、必要的原则，出于本指引所述的以下目的，收集和使用您在使用服务过程中主动提供或因使用知乎产品和/或服务而产生的个人信息。如果我们要将您的个人信息用于本指引未载明的其它用途，或基于特定目的将收集而来的信息用于其他目的，我们将以合理的方式向您告知，并再次征得您的同意。如果您对下列内容中的概念有疑问，请查阅第十一条的定义和解释。

在您使用知乎产品和/或服务时，我们需要/可能需要在如下两种情况下收集和使用您的个人信息： 为实现向您提供知乎产品和/或服务基本的业务功能（属于全部业务功能的一部分），您须授权我们收集、使用的必要信息。如您拒绝提供相应信息，您将无法使用相应知乎的产品和/或服务最基本的功能，即参与问答社区最基本的活动，包括提问和回答，得到适合您提问和/或回答的相关信息。 在您使用知乎产品和/或服务时，我们需要/可能需要在如下两种情况下收集和使用您的个人信息： 在提供相对基本的业务功能以外，我们一直在努力提供更丰富的知乎产品和/服务，以扩展、提高和加强您的使用体验；此类业务功能需要收集、使用相应的必要个人信息。如您拒绝提供，您将无法使用该等扩展体验的业务功能或无法达到我们拟达到的最佳扩展功能效果和体验，但并不会影响您正常使用知乎产品和/或服务基本的业务功能。

如您拒绝知乎收集您的任何个人信息，知乎将无法向您提供包括基本业务功能在内的本平台任何功能。在此情况下，知乎向您提供 「仅浏览」模式，旨在协助您在决定接受本指引前得到本平台的初步体验，即部分内容浏览，该等信息一般是基于其他用户同意本指引并提供他们的必要个人信息生成的内容。

提示您注意：由于不同的产品或服务所需的个人信息有所不同，我们还会在您使用具体产品和/或服务前通过页面提示、交互流程等方式征求您的同意，获取个人信息的范围以您同意的特定产品和/或服务的协议或规则为准。

由于我们向您提供的产品和/或服务是不断更新的，如果某一产品或服务未在前述内容中说明且收集了您的信息，我们会通过页面提示、交互流程、网站公告等方式另行向您说明信息收集的内容，以征得您的单独同意。该说明构成本指引的一部分，且该信息适用本指引的约定。

我们将在以下业务场景收集和使用您的个人信息：
帐号注册、登录、帐号绑定和安全
当您注册和登录知乎帐号时，我们依据实名制相关法律规定，需要您提供有效的中国大陆手机号码。请您注意，您向我们提供中国大陆手机号码是我们向您提供基本业务功能所必需的个人信息，如您拒绝提供，则我们无法向您提供知乎基本的业务功能，您将仅能使用「仅浏览」模式。

您也可以使用邮箱、海外手机号的方式注册和登录知乎，但我们会在您登录成功后的合法场景下，要求您进一步提供中国大陆手机号码以满足实名制的相关规定。

当需要修改帐号密码时，请您输入新密码，以完成重置。当您需要修改手机号时，请您输入新手机号和短信验证码。您可以自愿在设置页面绑定您的邮箱，此时，您需要提供您的邮箱帐号和我们发送给您的短信验证码进行验证操作。如您需设置信任设备，您需要向我们提供您正在使用的设备名称、设备类型、最后使用时间。当您开通知乎机构号时，您需要向我们提供您的邮箱地址、登录密码。

当您的帐号无法使用时，您可以通过帐号申诉进行找回。此时，您可以选择提供您帐号绑定的手机号码、邮箱、用过的密码、注册时间、常用访问城市、手机端登录常用设备、PC 端登录常用浏览器、知乎消费记录、曾用名、曾用一句话介绍或个人简介的任一几项，以便我们核实您的身份。请您知悉，您可自行选择填写前述信息中的一项或几项，但您提供的信息需达到足够判断出您有权使用该帐号的合理程度。';
        return view('pros.whatsapp.agreement.terms-privacy', compact('body'));
    }

    public function termsService(){
        $body = '软件服务及隐私条款
欢迎您使用软件及服务，以下内容请仔细阅读。
1、保护用户个人信息是一项基本原则，我们将会采取合理的措施保护用户的个人信息。除法律法规规定的情形外，未经用户许可我们不会向第三方公开、透漏个人信息。APP对相关信息采用专业加密存储与传输方式，保障用户个人信息安全，如果您选择同意使用APP软件， 即表示您认可并接受APP服务条款及其可能随时更新的内容。

2、我们将会使用您的以下功能：麦克风、喇叭、WIFI网络、蜂窝通信网络、手机基站数据、SD卡、短信控制、通话权限、蓝牙管理，如果您禁止APP使用以上相关服务和功能，您将自行承担不能获得或享用APP相应服务的后果。

3、为了提供更好的客户服务，基于技术必要性收集一些有关设备级别事件（例如崩溃）的信息，但这些信息并不能够让我们识别您的 身份。为了能够让APP定位服务更精确，可能会收集并处理有关您实际所在位置信息（例如移动设备发送的GPS信号），WI-FI接入点和 基站位置信息。我们将对上述信息实施技术保护措施，以最大程度保护这些信息不被第三方非法获得，同时，您可以自行选择拒绝我们基于技术必要性 收集的这些信息，并自行承担不能获得或享用APP相应服务的后果。

4、在您使用我们的产品或服务的过程中，我们可能：需要您提供个人信息，如姓名、电子邮件地址、电话号码、联系地址等以及注册或申请服务时需要 的其它类似个人信息；您对我们的产品和服务使用即表明您同意我们对这些信息的收集和合理使用。您可以自行选择拒绝、放弃使用相关产品或服务。

5、由于您的自身行为或不可抗力等情形，导致上述可能涉及您隐私或您认为是私人信息的内容发生被泄露、批漏，或被第三方获取、使用、转让等情形的，均由您自行承担不利后果，我们对此不承担任何责任。

6、我们拥有对上述条款的最终解释权。';
        return view('pros.whatsapp.agreement.terms-service', compact('body'));
    }
}
