<?php
include_once 'ksher_pay_sdk.php';
$appid='mch39730';
$privatekey=<<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIICYQIBAAKBgQCKzLlvv9P/CxWyxmDS5KNxZxuoVUunWEtTbhegkAZJI6IMMcxv
Qr1g7QnjFJmIgE6FDbIRb2B7tSUl3c4xPdCfQtkFkd//tZZZVIwsAHeZz0GjuwX/
6IYcNMVXaM0OE5+BBJOu+rqIu1b52WcLWWZHBUR7RmM46YtFRYzBUskJSwIDAQAB
AoGALJROZsbs1vh/cpjmljWbDRw5tNoYX1orb1NnwUkgy7LnJBWGfKGp44yeZVHD
ciULkw5wB8uG6JSopr3TaWaYka6LanfKL2Viir54cxnwYImDLMlb26jPkBpccmcP
SNbW6Euc4+6U7oA+NjkjP+jCsTkNo2ZxoGCHySuKN5trquECRQDeenF/vE82fjWh
H5y60uZDQ6GsM5H8i0eA2MeD7cuo3NjDefr5wKd87CkgtjI0QkuRYzhbA41vRJj6
kVWsylYsxjIJuwI9AJ+2lXcLma2TkutT4W1XeDjulG4n4EWZuy75icxuOrnQQXfQ
9vdUHRdmy0a+tLsK74vqOsZs7+Em41B9sQJFAIjts416yQYxB7DzU/NoenBL3+Ws
l91nm8qhoaqBYSe9RWyKVv4ApRUuBOItQRkI9Jm3B6h8t0AUaFNPv/tpTUChe4qh
AjxsTgqJUeqC1KI6xwUFet1h7hflo1DoodlXf4y8fruAKgNbVu9CxV188w5CCSzR
8haDkPEOge0hh5d6i+ECRQDWIvsecC7+QuackHw7C8WPKuUAehYTo1pplgIPAk0A
4vzbKWl5+Mh40wZKrvQRbs3vEjDamW/7RzjbE1G+JC0UmUL1dg==
-----END RSA PRIVATE KEY-----
EOD;

$time = date("Y-m-d H:i:s", time());
$class = new KsherPay($appid, $privatekey);

//1.接收参数
$input = file_get_contents("php://input");
tempLog("------notify data ".$time." begin------" );
$query = urldecode($input);
if( !$query){
    tempLog("NO RETURN DATA" );
    echo json_encode(array('result'=>'FAIL',"msg"=>'NO RETURN DATA'));
    exit;
}
//2.验证参数
$data_array = json_decode($query,true);
tempLog("notify data :".json_encode( $data_array) );
if( !isset( $data_array['data']) || !isset( $data_array['data']['mch_order_no']) || !$data_array['data']['mch_order_no']){
    tempLog("notify data FAIL" );
    echo json_encode(array('result'=>'FAIL',"msg"=>'RETURN DATA ERROR'));
    exit;
}
//3.处理订单
if( array_key_exists("code", $data_array)
    && array_key_exists("sign", $data_array)
    && array_key_exists("data", $data_array)
    && array_key_exists("result", $data_array['data'])
    && $data_array['data']["result"] == "SUCCESS"){
    //3.1验证签名
    $verify_sign = $class->verify_ksher_sign($data_array['data'], $data_array['sign']);
    tempLog("IN IF function sign :". $verify_sign );
    if( $verify_sign==1 ){
        //更新订单信息 change order status
        //....
        tempLog('change order status');
        echo json_encode(array('result'=>'SUCCESS',"msg"=>'OK'));
    } else {
        tempLog('VERIFY_KSHER_SIGN_FAIL');
        echo json_encode(array('result'=>'Fail',"msg"=>'VERIFY_KSHER_SIGN_FAIL'));
    }
}
//4.返回信息
tempLog("------notify data ".$time." end------" );



function tempLog( $string ){
    if( !$string ) return false;
    $file = dirname(__FILE__)."/notify_log_".date("Ymd").".txt";
    $handle = fopen( $file, 'a+');
    fwrite( $handle , $string."\r");
    fclose( $handle );
}