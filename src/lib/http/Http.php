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
        $this->header=array_merge($this->header, $header);
        return $this;
    }

    /**
     * 设置浏览器请求头
      * @return $this
     */
    public function setBrowserHeader(){
        $this->header=array_merge($this->header,[
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
            'X-Requested-With:XMLHttpRequest'
        ]);
        return $this;
    }

    public function setRerfer(string $referer){
        $this->header[]='Referer: '.$referer;
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
     * 设置代理服务器
      * @param string $proxy 代理服务器地址，格式为ip:port
      * @param string|null $auth 代理认证信息，格式为username:password，如果不需要认证则传null
      * @return $this
     */
    public function setProxy(string $proxy, ?string $auth=null){
        curl_setopt($this->ch, CURLOPT_PROXY, $proxy);
        if($auth){
            curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, $auth);
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
     * @param int $statusCode 响应状态码
     * @param array $responseHeaders 响应头
     * @return mixed 返回格式化后的响应结果
     */
    public function get(&$statusCode=null,&$responseHeaders=[]){
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($this->ch, CURLOPT_POST, 0);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
        $response = curl_exec($this->ch);

        if($response === false){
            $statusCode = 0;
            $responseHeaders = [];
            curl_close($this->ch);
            return null;
        }

        $statusCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        $header_text = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $responseHeaders = $this->parseHeaders($header_text);

        curl_close($this->ch);
        return $this->formatResponse($body);
    }

    /**
     * 发送POST请求，数据以表单形式提交
      * @param array $data POST请求的数据数组
      * @param int $statusCode 响应状态码
      * @param array $responseHeaders 响应头
      * @return mixed 返回格式化后的响应结果
     */
    public function postForm(array $data,&$statusCode=null,&$responseHeaders=[]){
        $this->header[]='Content-type:application/x-www-form-urlencoded';
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
        $response = curl_exec($this->ch);

        if($response === false){
            $statusCode = 0;
            $responseHeaders = [];
            curl_close($this->ch);
            return null;
        }

        $statusCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        $header_text = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $responseHeaders = $this->parseHeaders($header_text);

        curl_close($this->ch);
        return $this->formatResponse($body);
    }

    /**
     * 发送POST请求，数据以JSON形式提交
      * @param array $data POST请求的数据数组
      * @param int $statusCode 响应状态码
      * @param array $responseHeaders 响应头
      * @return mixed 返回格式化后的响应结果
     */
    public function postJson(array $data,&$statusCode=null,&$responseHeaders=[]){
        $this->header[]='Content-Type: application/json; charset=utf-8';
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($data,JSON_UNESCAPED_UNICODE));
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
        $response = curl_exec($this->ch);

        if($response === false){
            $statusCode = 0;
            $responseHeaders = [];
            curl_close($this->ch);
            return null;
        }

        $statusCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        $header_text = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $responseHeaders = $this->parseHeaders($header_text);

        curl_close($this->ch);
        return $this->formatResponse($body);
    }

    /**
     * 下载文件
     * @param $save_to 保存位置+文件名称
     * @param int $statusCode 响应状态码
     * @param array $responseHeaders 响应头
     * @return bool 下载是否成功
     */
    public function downLoadFile(string $save_to,&$statusCode=null,&$responseHeaders=[]):bool
    {
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        $response = curl_exec($this->ch);

        if($response === false){
            $statusCode = 0;
            $responseHeaders = [];
            curl_close($this->ch);
            return false;
        }

        $statusCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        $header_text = substr($response, 0, $header_size);
        $file_content = substr($response, $header_size);
        $responseHeaders = $this->parseHeaders($header_text);

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

    /**
     * 解析HTTP响应头
     * @param string $header_text 原始响应头文本
     * @return array 解析后的响应头数组
     */
    private function parseHeaders(string $header_text):array{
        $headers = [];
        $lines = explode("\r\n", $header_text);
        foreach($lines as $line){
            if(strpos($line, ':') !== false){
                list($key, $value) = explode(':', $line, 2);
                $headers[trim($key)] = trim($value);
            }
        }
        return $headers;
    }
}