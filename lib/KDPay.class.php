<?php
header( "content-type:text/html; charset=gbk" );
class KDPay{
    //商户ID与商户秘钥
    private $P_UserId;
    private $SafeStr;

    //名称或备注
    private $P_Notice;

    //充值类型id 具体数值查看对接文档
    private $P_ChannelId;
    //充值金额与充值价格
    private $P_FaceValue;
    private $P_Price;
    private $P_Quantity=1;
    //银行充值类型
    private $P_Description ="";

    //卡类充值时的卡号卡密参数
    private $P_CardId = "";
    private $P_CardPass ="";

    private $P_OrderId;

    //卡类充值网关
    public $CardUrl = "https://gateway.999pays.com/pay/KDCard.aspx";
    //非卡类充值网关
    public $noCardUrl = "https://gateway.999pays.com/pay/KDBank.aspx" ;
    //订单查询接口
    public $queryUrl = "http://gateway.999pays.com/pay/query.aspx";

    //异步回调地址
    public $P_Result_URL = "";
    //同步回调地址
    public $P_Notify_URL = "";

    //构造函数，设置商户id和秘钥
    public function __construct($UserId,$SafeW)
    {
        if(empty($UserId) || empty($SafeW))
        {
            exit('<script>alert("类初始化出错！(商户ID或秘钥不能为空！)");window.history.go(-1);</script>');
        }
        $this->P_UserId = $UserId;
        $this->SafeStr = $SafeW;

    }

    //设置名称或备注
    public function setPayNotice($payNotice)
    {
        $this->P_Notice = $payNotice;
    }
    public function getPayNotice()
    {
        return $this->P_Notice;
    }

    //设置充值方式
    public function setPayChannel($payDescription=1)
    {
        if($payDescription < 10000)
        {
            $this->P_ChannelId = $payDescription;
        }
        else {
            $this->P_ChannelId = 107;
            $this->P_Description = $payDescription;
        }

    }
    public function getPayChannel()
    {
        return $this->P_ChannelId;
    }
    //设置支付金额
    public function setFaveValue($payMoney)
    {
        if(!is_numeric($payMoney))
        {
            die('<script>alert("提交金额出错！'.$payMoney.'");window.history.go(-1);</script>');
        }
        $this->P_FaceValue = $payMoney;
        $this->P_Price = $payMoney;
    }
    //生成订单
    public function generateOrder()
    {
        $orderSn = "KDP".date('YmdHis'.rand(0,999),time());
        $this->P_OrderId = $orderSn;
    }
    public function getOrder()
    {
        return $this->P_OrderId;
    }

    //如果是卡类支付，设置卡号卡密
    public function setCard($card_id="",$card_pass="")
    {
        $this->P_CardId = $card_id;
        $this->P_CardPass = $card_pass;
    }
    //获取签名字符串
    public function getPostKey()
    {
        $str = $this->P_UserId.'|'.$this->P_OrderId.'|'.$this->P_CardId.'|'.$this->P_CardPass
            .'|'.$this->P_FaceValue.'|'.$this->P_ChannelId.'|'.$this->SafeStr;

        $post_key = md5($str);
        return $post_key;
    }


    //设置回调地址和同步跳转地址
    public function setNotifyAndResult($notify_url,$result_url="http://www.baidu.com")
    {
        $this->P_Notify_URL = $notify_url;
        $this->P_Result_URL = $result_url;
    }


    //支付选择，1为网银类支付，2为卡类支付
    public function getRedirectUrl($pay_type ='1')
    {
        //生成订单号
        $this->generateOrder();
        if($pay_type == 1)
        {
            $domain_url = $this->noCardUrl;
        }
        else
        {
            $domain_url = $this->CardUrl;
        }

        $url = $domain_url."?P_UserId=".$this->P_UserId;
        $url.= "&P_OrderId=".$this->P_OrderId;
        $url.= "&P_CardId=".$this->P_CardId;
        $url.= "&P_Notic=".$this->P_Notice;
        $url.= "&P_CardPass=".$this->P_CardPass;
        $url.= "&P_FaceValue=".$this->P_FaceValue;
        $url.= "&P_ChannelId=".$this->P_ChannelId;
        $url.= "&P_Description=".$this->P_Description;
        $url.= "&P_Price=".$this->P_Price;
        $url.= "&P_Quantity=".$this->P_Quantity;
        $url.= "&P_PostKey=".$this->getPostKey();
        $url.= "&P_Result_URL=".$this->P_Result_URL;
        $url.= "&P_Notify_URL=".$this->P_Notify_URL;
        $url.= "&P_Subject=我的项目";
        return $url;
    }


    //查询订单
    public function queryOrderUrl($order_id,$channel_id,$faceValue,$card_id="")
    {
        //获取查询的postKey
        $queryStr  = 'P_UserId='.$this->P_UserId;
        $queryStr .= '&P_OrderId='.$order_id;
        $queryStr .= '&P_ChannelId='.$channel_id;
        $queryStr .= '&P_CardId='.$card_id;
        $queryStr .= '&P_FaceValue='.$faceValue;
        $md5Str = $queryStr.'&P_PostKey='.$this->SafeStr;

        $postKey = md5($md5Str);
        $P_PostKey = iconv('UTF-8','gb2312//IGNORE',$postKey);

        $url = $this->queryUrl.'?'.$queryStr.'&P_PostKey='.$P_PostKey;

        return $url;
    }



}

?>