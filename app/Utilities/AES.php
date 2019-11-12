<?php
namespace App\Utilities;
class AES {
    protected $key;
    protected $data;
    protected $method;
    protected $options = 0;

    function __construct($data = null, $key = null,$blockSize = 256, $mode = 'CBC') {
        $this->setData($data);
        $this->setKey($key);
        $this->setMethode($blockSize, $mode);
        $this->setIv($key);
    }

    private function setData($data) {
        $this->data = $data;
    }
    
    private function setKey($key) {
        $this->key = $key;
    }
    
    private function setMethode($blockSize, $mode = 'CBC') {
        if($blockSize==192 && in_array('', array('CBC-HMAC-SHA1','CBC-HMAC-SHA256','XTS'))){
            $this->method=null;
            return [];
        }
        $this->method = 'AES-' . $blockSize . '-' . $mode;
    }
    
    private function validateParams() {
        if ($this->data != null && $this->method != null ) {
            return true;
        } else {
            return FALSE;
        }
    }
    
    private function setIv($iv){
        if($iv != ''){
            $this->iv = $iv;
        }else{
            return [];
        }
    }

    private function getIV() {
        return $this->iv;
    }
    
    public function encrypt() {
        if ($this->validateParams()) { 
            return trim(openssl_encrypt($this->data, $this->method, $this->key, $this->options,$this->getIV()));
        } else {
            return [];
        }
    }

    public function decrypt() {
        if ($this->validateParams()) {
            $ret=openssl_decrypt($this->data, $this->method, $this->key, $this->options,$this->getIV());
            
            return   json_decode(trim($ret),true); 
        } else {
            return [];
        }
    }
}