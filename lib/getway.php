<?php
require ('config.php');

//获取页面传递的充值参数
$notice = $_POST['notice'];
$faveValue = $_POST['faceValue'];
$pd_id = $_POST['payType'];

//同步和异步跳转地址
$notify_url = "http://".dirname(dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']))."/notify_url.php";
$result_url = "http://".dirname(dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']))."/result_url.php";

//设置名称或备注
$kdPay->setPayNotice(iconv("UTF-8","GBK", $notice));
//设置充值方式
$kdPay->setPayChannel($pd_id);
//设置金额
$kdPay->setFaveValue($faveValue);
//设置同步和异步地址
$kdPay->setNotifyAndResult($notify_url,$result_url);
//获取跳转地址
$redirect_url = $kdPay->getRedirectUrl();

/************************
 * 下步进行订单入库保存
 * 订单号为$kdpay->getOrder();
 */
//下面这句是提交到API
header("location:$redirect_url");
