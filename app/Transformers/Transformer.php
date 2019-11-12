<?php
namespace App\Transformers;

use App\Utilities\AES;
use Response;

/**
 * Class Transformer.
 *
 * @package app\Transformer
 *
 * @author Rami Badran <ramibadran.82@gmail.com>
 */
class Transformer extends Respond{
    /**
     * @var Manager
     */
    private $fractal;
    
    /**
     * @var tags deleted
     */
    public $statusCode;
    
    /**
     * @var $customMsg
     */
    public $customMsg;
    
    /**
     * @var $customCode
     */
    public $customCode;
    
    /**
     * @var $header
     */
    public $header = array('Content-Type'=> 'application/json','API-Header'	=>'Data-in-Header');
    
    function __construct($item, $itemTranformer, $fractal,$privateKey = ''){
        $this->item            = $item;
        $this->itemTransformer = $itemTranformer;
        $this->fractal         = $fractal;
        $this->privateKey      = $privateKey;
    }
    
    public function setStatusCode($stausCode){
        $this->statusCode = $stausCode;
    }
    
    public function getStatusCode(){
        return $this->statusCode;
    }
    
    public function setCustomCode($customCode){
        $this->customCode = $customCode;
    }
    
    public function getCustomCode(){
        return $this->customCode;
    }
    
    public function setCustomMessage($customMsg){
        $this->customMsg = $customMsg;
    }
    
    public function getCustomMessage(){
        return $this->customMsg;
    }
    
    public function getHeader(){
        return $this->header;
    }
    
    public function setHeader($headers){
        $this->header = array_merge($this->header,$headers);
    }
    
    public function respond($type='item',$encrypt=0){       
        $data = current($this->scope($this->item, $this->itemTransformer, $this->fractal,$type));
        if($encrypt == 1){
            $aes = new AES(json_encode($data),$this->privateKey);
            $scope = $aes->encrypt();
        }else{
            $scope = $data;
        }
        
        return Response::json([
            'header'		 => $this->getHeader(),
            'custom_message' => $this->getCustomMessage(),
            'custom_code'    => $this->getCustomCode(),
            'data'           => $scope,
        ], $this->getStatusCode(), $this->getHeader());
    }
}