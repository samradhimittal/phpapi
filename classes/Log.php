<?php
class Log{
    
    // CHANGE THE DB INFO ACCORDING TO YOUR DATABASE
    private $rightnow;
    private $url ;
    private $userId;
    private $filename;
    
    public function __construct($userId,$status){ 
        $link = $_SERVER['PHP_SELF'];
        $link_array = explode('/',$link);
        $page = end($link_array);      
        $this->rightnow =  date('Y-m-d H:i:s');
        $this->url = $page;
        $this->userId = $userId;
        $this->status = $status;
        $this->filename = "logfile.log";
    }

    public function addLog(){
        if(!file_exists($this->filename)){
            fopen($this->filename, "w"); 
        }
        $filesize = filesize($this->filename);
        if($filesize==0){
            $logmessage = $this->rightnow.",".$this->status.",".$this->url.",".$this->userId;
        }else{
            $logmessage = "\n".$this->rightnow.",".$this->status.",".$this->url.",".$this->userId;
        }
       
        file_put_contents("logfile.log", $logmessage, FILE_APPEND);
    }
}