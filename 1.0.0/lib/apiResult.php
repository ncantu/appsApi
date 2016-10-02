<?php 

error_reporting(E_All);

class ApiResult {
    
    private $contentType = 'Content-type:application/json;charset=utf-8';
    private $resultStr = array();
    private $resultState;
    private $resultCode;
    private $resultDocUrlList = array();
    private $resultMsg;
    private $plaque;
    private $log = array();
    private $cacheDir = '../../cache/';
    private $confDir = '../conf/';
    private $cacheState = true;
    private $codeList = array();
    
    public $cacheFile;
    public $resultService = '';

    public function __construct() {
        
        $codeList = file_get_contents($this->confDir.'code.json');        
        $this->codeList = json_decode($codeList);
    }
    
    public function resultDocUrlListAdd($resultDocUrl) {
    
        $this->resultDocUrlList[] = $resultDocUrl;
        
        return true;
    }
    public function codeInit($code , $msg = false) {
        
        foreach($this->codeList as $codeInfos) {
            
            if($codeInfos->code === $code) {

                $m = $codeInfos->msg;
                
                if($msg !== false) {
                
                    $m = $msg;
                }
                $this->resultMsgSet($m);                
                $this->resultState = $codeInfos->state;
                $this->resultCodeSet($codeInfos->code);                
                $this->logAdd($codeInfos);
                break;
            }
        }        
        return true;
    }
    
    public function resultStrAdd($resultStr){
        
        $this->resultStr[] = $resultStr;
        
        return true;
    }
    
    public function resultMsgSet($msg){

        $this->resultMsg = $msg;

        $this->resultStrAdd($msg);
        
        return true;
    }
    
    public function resultStateOn(){

        $this->resultState = true;
        
        return true;
    }
    
    public function resultStateOff(){

        $this->resultState = false;
        
        return true;
    }
    
    public function resultCodeSet($code){

        $this->resultCode = $code;
        
        return true;
    }
    
    public function plaqueSet($plaque){

        $this->plaque = $plaque;        

        $this->logAdd( $this->plaque);
        
        return true;
    }
    
    public function logAdd($log){
        
        $this->log[] = $log;
        
        return true;
    }
    
    public function cacheFileSet($cacheFile){
        
        $this->cacheFile = $this->cacheDir.$cacheFile;
        
        return true;
    }
    
    public function cacheStateOn(){
        
        $this->cacheState = true;
        
        return true;
    }
    
    public function cacheStateOff(){
        
        $this->cacheState = false;
        
        return true;
    }
    
    public function cacheRun(){
        
        if($this->cacheState === false || is_file($this->cacheFile) === false) {
            
            return true;
        }
        header($this->contentType);
    
        echo file_get_contents($this->cacheFile);
    
        exit();
    }
    
    public function export(){
        
        $export = new stdClass();
        
        foreach($this as $k => $v){
        
            $export->$k = $v;
        }
        $export = json_encode($export, JSON_PRETTY_PRINT);
        
        return $export;
    }

    public function send() {
                
        header($this->contentType);
        
        $response = $this->export();
        
        echo $response;
        
        if($this->cacheState === true) {
        
            $this->cacheStateOn();
            $response = $this->export();
            
            file_put_contents($this->cacheFile, $response);
        }
        exit();
    }
    
    public function sendEmpty() {
        
        $this->cacheFileSet('empty.json');
        $this->send();
    }
}

?>