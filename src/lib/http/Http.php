<?php
namespace iboxs\basic\lib\http;
/**
 * Http请求方法
 */
class Http{
    protected array $header=[];
    /**
     * curl句柄
     * @var \CurlHandle
     */
    protected $ch;

    public function __construct(
       protected string $url,
       protected string $format='json'
    ){
        $this->ch=curl_init($this->url);
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 3);//设置连接超时时间
    }

    /**
     * 设置请求头
     * @param array $header 请求头数组
     * @return $this
     */
    public function setHeader(array $header){
        $this->header=$header;
        return $this;
    }

    /**
     * 设置浏览器请求头
      * @return $this
     */
    public function setBrowserHeader(){
        $this->header=array_merge($this->header,[
            'User-Agent: User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
            'X-Requested-With:XMLHttpRequest'
        ]);
        return $this;
    }

    /**
     * 设置SSL验证
      * @param bool $verify 是否验证SSL证书，默认为true
      * @return $this
     */
    public function setSslVerify(bool $verify=true){
        if($verify){
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 2);
        }else{
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        return $this;
    }

    /**
     * 设置请求超时时间
     * @param int $timeout 超时时间，单位为秒
     * @return $this
     */
    public function setTimeout($timeout){
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);
        return $this;
    }

    /**
     * 发送普通GET请求
     */
    public function get(){
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($this->ch, CURLOPT_POST, 0);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        $rst = curl_exec($this->ch);
        curl_close($this->ch);
        return $this->formatResponse($rst);
    }

    /**
     * 发送POST请求，数据以表单形式提交
      * @param array $data POST请求的数据数组
      * @return mixed 返回格式化后的响应结果
     */
    public function postForm(array $data){
        $this->header[]='Content-type:application/x-www-form-urlencoded';
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        $rst = curl_exec($this->ch);
        curl_close($this->ch);
        return $this->formatResponse($rst);
    }

    /**
     * 发送POST请求，数据以JSON形式提交
      * @param array $data POST请求的数据数组
      * @return mixed 返回格式化后的响应结果
     */
    public function postJson(array $data){
        $this->header[]='Content-Type: application/json; charset=utf-8';
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($data,JSON_UNESCAPED_UNICODE));
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        $rst = curl_exec($this->ch);
        curl_close($this->ch);
        return $this->formatResponse($rst);
    }

    /**
     * 下载文件
     * @param $file_url 下载地址
     * @param $save_to 保存位置+文件名称
     * @return bool 下载是否成功
     */
    public function downLoadFile(string $file_url, string $save_to):bool
    {
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_URL, $file_url);
        $file_content = curl_exec($this->ch);
        curl_close($this->ch);
        $downloaded_file = fopen($save_to, 'w');
        fwrite($downloaded_file, $file_content);
        fclose($downloaded_file);
        if (!file_exists($save_to)) {
            return false;
        }
        return true;
    }

    private function formatResponse($response){
        if($this->format=='json'){
            return json_decode($response,true);
        }
        return $response;
    }
}