<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/19
 * Time: 17:14
 */
/*氧气支付宝支付*/
class OxygenAliPay
{
    public $gatewayUrl ='https://openapi.alipay.com/gateway.do';//支付宝网关
    private $appId=''; //appid
    private $rsaPrivateKey=''; //商户私钥
    private $format='json';
    private $charset='utf-8';
    private $signType='RSA2';
    private $alipayrsaPublicKey=''; //支付宝公钥
    public $powerUrl='';  //官方sdk目录
    private $returnUrl='';//同步跳转地址
    private $notifyUrl='';//异步通知地址
    private $sendData=null; //向支付宝发送的数据
    public function __construct($appId,$rsaPrivateKey,$alipayrsaPublicKey,$format='json',$charset='utf-8',$signType='RSA2'){
        $this->appId=$appId;
        $this->rsaPrivateKey=$rsaPrivateKey;
        $this->alipayrsaPublicKey=$alipayrsaPublicKey;
        $this->format=$format;
        $this->charset=$charset;
        $this->signType=$signType;
    }
    /*引用sdk*/
    public function includePower($url=''){
        $this->powerUrl=$url;
        require_once($this->powerUrl . '/' . 'AopSdk.php');
        return $this;
    }
    /*电脑网站支付*/
    public function pcWebPay(){
        $c = new AopClient();
        $c->gatewayUrl = $this->gatewayUrl;
        $c->appId = $this->appId;
        $c->rsaPrivateKey =$this->rsaPrivateKey;
        $c->format = $this->format;
        $c->charset= $this->charset;
        $c->signType= $this->signType;
        $c->alipayrsaPublicKey =$this->alipayrsaPublicKey;
        $request = new AlipayTradePagePayRequest ();
        $request->setReturnUrl($this->returnUrl);
        $request->setNotifyUrl($this->notifyUrl);
        $request->setBizContent($this->sendData);
        $result = $c->pageExecute ($request);
         return $result;
    }
    /*生成订单json*/
    public function createOrderJson($order){
        $this->sendData=json_encode($order);
        return $this;
    }
    /*退款*/
    public function refund(){
        $c = new AopClient();
        $c->gatewayUrl = $this->gatewayUrl;
        $c->appId = $this->appId;
        $c->rsaPrivateKey =$this->rsaPrivateKey;
        $c->format = $this->format;
        $c->charset= $this->charset;
        $c->signType= $this->signType;
        $c->alipayrsaPublicKey =$this->alipayrsaPublicKey;
        $request =  new AlipayTradeRefundRequest ();
        $request->setBizContent($this->sendData);
        $result = $c->execute ( $request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        return $result->$responseNode;
    }
    /*生成退款json*/
    public function createRefundJson($order){
        $this->sendData=json_encode($order);
        return $this;
    }
}