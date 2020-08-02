<?php


namespace app\controller;
use think\Controller;

class Rsa extends Controller
{
    private $pubKey = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDr6H/ictALLsV9/63lPFSYDPQKgRwEM2FiewfR/BYaPGfpgdl8lelNYqFxnqBRKbGnbFOwOxOu7oiiPYaJxcSU94hId3S0/UsSXyRfTaHT8ZZv+5luikQAG62hwkxqcSdL3aEMbqsHRfQ9RXiFAneiJJwZ1D0nHPANfBA4UN+OXQIDAQAB';
    private $priKey = 'MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAOvof+Jy0AsuxX3/reU8VJgM9AqBHAQzYWJ7B9H8Fho8Z+mB2XyV6U1ioXGeoFEpsadsU7A7E67uiKI9honFxJT3iEh3dLT9SxJfJF9NodPxlm/7mW6KRAAbraHCTGpxJ0vdoQxuqwdF9D1FeIUCd6IknBnUPScc8A18EDhQ345dAgMBAAECgYEAoNlVIQShn45TcBa97dhV4ZqrZuIjRSX3V5uFeIKGW3smastzjAP3ICGI7Jx4uP5RuFMfOMD/Kb5QgTasHhIvdwe0kuMdUqd8YCLgZaV1u02GWkp5I7bG2HRKAqfrpaExeOJt3Iqmggt208P3BNQLTOa2NFtNqT+onI1dGwbC120CQQD2BbkrXPv+wGAKkcqIK77Bkrwpg7Iqj91uyVHBAleWAgfWDFA3rJb8jDCARHte2ehMImmhbsQmVBjdI1DNdYWLAkEA9XnEoVVIL5IA09s0XtL/Na065loDTJsQZiumdi6VMn6zWafu6GFhS0w5DQdkjtA7qhwpftjVRaWtK0DX4qpItwJAKxGrbfT0RI/HAHKvYxFNbrPSbu4YNa1D1Y422rQfQyqN1qIHNQfo0sN0BjB27I73RMTNey5Z9l/IjoYNMjq9qwJABChZ0jm1jUi1xuDRlEGSnQAgHUKtB6Egt/pJSXskf8RxmTUk8L6lfTb/SF81rs2MFSeA9GsLwbA6rJ7eiTJFJQJBAJnixcdpF6knRxyOUDhWoa8uYmnUdcyrfo4dnNyliJbNTTSw0LJAGZsCbo9EDqQIxDrqDa9Xqj0yz6UT1JM37Tk=';
    private $ssl_param = [
        array('config' => OPENSSL_CNF_PATH),
        array('private_key_bits' => 512),
    ];
    function index(){
        $this->generateKey();
        //$this->priKey = $this->formatPriKey($this->priKey);
        //$this->pubKey = $this->formatPubKey($this->pubKey);
        //显示公私密钥对
        echo "私钥为:".$this->priKey.'</br>';
        echo "公钥为:".$this->pubKey.'</br>';
        $params = [
            "avatar"=>"满脑子骚操作的大湿",
            "content"=>"DAISIKIDEYO!",
        ];
        //获取预处理字符串
        $msgString = $this->getSignString($params);
        echo '待加密的字符串数据为:--'.$msgString.'</br>';
        //实现签名过程:
        //获取签名
        $sign = $this->getSign($msgString,$this->priKey);
        //验证签名
        $checkRes = $this->checkSign($this->pubKey,$sign,$msgString);
        if ($checkRes){
            echo '签名验证成功'.'</br>';
        }else{
            echo '签名验证失败'.'</br>';
        }
        //实现RSA加解密过程
        $pubKeyEncode = $this->pubKey_encrypt_cipherText($msgString,$this->pubKey);
        $this->private_decode($pubKeyEncode,$this->priKey);
    }
    function generateKey()
    {
        //创建公钥和私钥
        $res = openssl_pkey_new($this->ssl_param); #此处512必须不能包含引号
        print_r($res);
        //提取私钥
        openssl_pkey_export($res, $private_key);
        $this->priKey = $private_key;
        //生成公钥
        $public_key = openssl_pkey_get_details($res);
        $this->pubKey = $public_key = $public_key["key"];
    }
    /**
     * 获取待签名字符串
     * @param    array     $params 参数数组
     * @return   string
     */
    function getSignString($params){
        unset($params['sign']);
        ksort($params);
        reset($params);

        $pairs = array();
        foreach ($params as $k => $v) {
            if(!empty($v)){
                $pairs[] = "$k=$v";
            }
        }

        return implode('&', $pairs);
    }
    /**私钥格式化
     * @param string $priKey 输入私钥序列
     * @return string 返回格式化后的私钥
     */
    function formatPriKey($priKey)
    {
        $fKey = "---YUAN YI ZHE ZHI---\n";
        $len = strlen($priKey);
        for ($i = 0; $i < $len;) {
            $fKey = $fKey . substr($priKey, $i, 64) . "\n";
            $i += 64;
        }
        $fKey .= "---CHUN RI YE QIONG---";
        return $fKey;
    }
    /**公钥格式化
     * @param string $pubKey 输入公钥序列
     * @return string 返回格式化后的公钥
     */
    function formatPubKey($pubKey)
    {
        $fKey = "---GONG ZHU SHI XIANG---\n";
        $len = strlen($pubKey);
        for ($i = 0; $i < $len;) {
            $fKey = $fKey . substr($pubKey, $i, 64) . "\n";
            $i += 64;
        }
        $fKey .= "---KU LU MI---";
        return $fKey;
    }
    /**私钥签名
     * 生成签名
     * @param    string     $signString 待签名源始字符串
     * @param    [type]     $priKey     私钥
     * @return   string     base64结果值
     */
    function getSign($signString,$priKey)
    {
        //获取私钥
        $privKeyId = openssl_pkey_get_private($priKey);
        $signature = '';
        //生成签名
        openssl_sign($signString, $signature, $privKeyId);
        echo "私钥签名后的内容为:".base64_encode($signature).'</br>';
        //释放资源
        openssl_free_key($privKeyId);
        //返回base转码后的签名
        return base64_encode($signature);
    }
    /**公钥验签
     * 校验签名 -- 解签过程
     * @param string $pubKey 公钥
     * @param string $msgSequence 待解签源始字符序列
     * @param string $signSequence 签名字符序列
     * @param string $signature_alg 签名方式 比如 sha1WithRSAEncryption 或者sha512
     * @return   bool 返回值为布尔值(真|假)
     */
    function checkSign($pubKey,$signSequence,$msgSequence,$signature_alg=OPENSSL_ALGO_SHA1)
    {
        //获取公钥
         $publicKeyId = openssl_pkey_get_public($pubKey);
         //验证签名
         $result = openssl_verify($msgSequence, base64_decode($signSequence), $publicKeyId,$signature_alg);
         //释放签名
         openssl_free_key($publicKeyId);
         return $result === 1 ? true : false;
    }
    /**公钥加密
     * @param    string     $sign_str   待加密字符串
     *@param    string     $public_key  公钥
     *@param    string     $signature_alg 加密方式
     *@return    string
     */
    function pubKey_encrypt_cipherText($sign_str,$public_key,$signature_alg=OPENSSL_ALGO_SHA1)
    {
        $public_key = openssl_pkey_get_public($public_key);//加载公钥
        //openssl_sign($sign_str,$signature,$public_key,$signature_alg);//生成公钥加密数据
        openssl_public_encrypt($sign_str, $cipher, $public_key);//生成公钥加密数据
        $cipher = base64_encode($cipher);//加密之后会有特殊字符,用base64转码一下
        echo '公钥加密后的字符数据为:'.$cipher.'</br>';
        openssl_free_key($public_key);
        return $cipher;
    }
    /**私钥解密
     *@param    string     $pubKey_EncryptStr 传入公钥加密后的密文
     *@param    string     $private_key 私钥
     *@param    string     $signature_alg 解密方式
     *@return     bool
     */
    function private_decode($pubKey_EncryptStr,$private_key,$signature_alg=OPENSSL_ALGO_SHA1)
    {
        $private_key = openssl_get_privatekey($private_key);
        openssl_private_decrypt(base64_decode($pubKey_EncryptStr), $decrypted, $private_key);//私钥解密
        echo '私钥解密后的字符数据为:' . $decrypted . '</br>';
        openssl_free_key($private_key);
        return true;//false or true
    }
}