<?php

trait Response
{
    public function send($content)
    {
        header('Content-Type: application/json');
        if(is_object($content) || is_array($content))  echo json_encode($content);
        else echo $content;
        die();
    }
    public function send_success($content = false)
    {
        $out = [
            'success' => true
        ];

        if($content) $out['data'] = $content;
        return $this->send($out);
    }
    public function send_error($content = false)
    {
        $out = [
            'success' => false
        ];

        if($content) $out['data'] = $content;
        return $this->send($out);
    }

    public function get_bearer_token()
    {
        $token = null;
        $headers = apache_request_headers();
        if(isset($headers['Authorization'])){
          $matches = array();
          preg_match('/Bearer (.*)/', $headers['Authorization'], $matches);
          if(isset($matches[1])){
            $token = $matches[1];
          }
        } 

        return $token;
    }

    public function is_admin(){
        return $this->user->is_admin == true;
    }

    public function middleware_user()
    {
        if(!$this->user){
            $this->send_error('Permission denied');
        }
    }
    public function middleware_admin()
    {
        if((bool) $this->user->is_admin !== true){
            $this->send_error('Permission denied');
        }
    }

}