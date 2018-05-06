<?php

/***
 * Class 微信支付签名
 */
class Sign {
        const KEY = '微信支付后台获取';
        /**
         * 获取签名
         * @param array $arr
         * @return string
         */
        public function getSign($arr){
            //去除空值
            $arr = array_filter($arr);
            if(isset($arr['sign'])){
                unset($arr['sign']);
            }
            //按照键名字典排序
            ksort($arr);
            //生成url格式的字符串
            $str = $this->arrToUrl($arr) . '&key=' . self::KEY;
            return strtoupper(md5($str));
        }

        /****
         * 验证签名
         */
        public function checkSign($arr){
            $sign = $this -> getSign($arr);
            if ($sign == $arr['sign']){
                return true;
            }else{
                return false;
            }
        }
        /**
         * 获取带签名的数组
         * @param array $arr
         * @return array
         */
        public function setSign($arr){
            $arr['sign'] = $this->getSign($arr);;
            return $arr;
        }
        /**
         * 数组转URL格式的字符串
         * @param array $arr
         * @return string
         */
        public function arrToUrl($arr){
            return urldecode(http_build_query($arr));  //解决数组中含有汉字问题
        }
}