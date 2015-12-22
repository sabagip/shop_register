<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');

class Login extends REST_Controller {
    
    function __construct() {
        parent::__construct();
    }

	
	public function logueo_get(){
            $user = $this->get('usuario');
            $pass = $this->get('pass');
            
            if($user == NULL ):
                $this->response("Usuario vacio", 400);
            endif;
            
            
            $response = $this->m_consultas->login($user, $pass);
            
            $data = array();
            if($response == FALSE):
                $data ["response"] = "userPassFail";
                $this->response($data, 400);
            endif;
            
            
            $data['response'] = $response;
            
            $this->response($data, 200);
            
           ;
	}
}
