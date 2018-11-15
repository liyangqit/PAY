<?php

 /***
  *  支付宝支付签名生成
  */
//rsa方式  生成签名和验签  支持RSA RSA2 方式
 class Rsa2{
     /*
      * RSA签名
      * @param $data 待签名数据
      * @param $private_key 私钥字符串
      * return 签名结果
      */
     function rsaSign($data, $private_key , $sign = 'RSA') {

         $search = [
             "-----BEGIN RSA PRIVATE KEY-----",
             "-----END RSA PRIVATE KEY-----",
             "\n",
             "\r",
             "\r\n"
         ];

         $private_key=str_replace($search,"",$private_key);
         $private_key=$search[0] . PHP_EOL . wordwrap($private_key, 64, "\n", true) . PHP_EOL . $search[1];
         $res=openssl_get_privatekey($private_key);

         if($res)
         {
             if ($sign == 'RSA2') {
                 openssl_sign($data, $sign,$res,OPENSSL_ALGO_SHA256);
             }elseif ($sign == 'RSA') {
                 openssl_sign($data, $sign,$res);
             }
             openssl_free_key($res);
         }else {
             exit("私钥格式有误");
         }
         $sign = base64_encode($sign);
         return $sign;
     }

     /*
      * RSA验签
      * @param $data 待签名数据
      * @param $public_key 公钥字符串
      * @param $sign 要校对的的签名结果
      * return 验证结果
      */
     function rsaCheck($data, $public_key, $sign)  {
         $search = [
             "-----BEGIN PUBLIC KEY-----",
             "-----END PUBLIC KEY-----",
             "\n",
             "\r",
             "\r\n"
         ];
         $public_key=str_replace($search,"",$public_key);
         $public_key=$search[0] . PHP_EOL . wordwrap($public_key, 64, "\n", true) . PHP_EOL . $search[1];
         $res=openssl_get_publickey($public_key);
         if($res)
         {
             if ( $sign == 'RSA') {
                 $result = (bool)openssl_verify($data, base64_decode($sign), $res);
             } elseif ($sign == 'RSA2') {
                 $result = (bool)openssl_verify($data, base64_decode($sign), $res,OPENSSL_ALGO_SHA256);
             }
             openssl_free_key($res);
         }else{
             exit("公钥格式有误!");
         }
         return $result;
     }


     /***
      * 如果使用RSA2只需要在签名和验签的函数里面多增加一个参数OPENSSL_ALGO_SHA256就可以了，改进如下：
      */

     /***
      * 签名 RSA2
                 openssl_sign($data, $sign,$res,OPENSSL_ALGO_SHA256);

        验签 RSA2
                 openssl_verify($data, base64_decode($sign), $res,OPENSSL_ALGO_SHA256);
      */

 }