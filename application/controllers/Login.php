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
            
            if($pass === NULL ):
                $pass = "";
            endif;
            
            if($user == ""):
                $this->response("No se pudo encontrar nada", 400);
            endif;
            
            $response = $this->m_consultas->login($user, $pass);
            
            if($response == FALSE):
                $this->response("No se pudo encontrar nada", 400);
            endif;
            
            $result['response'] = $response;
            
            $this->response($result, 200);
            
           ;
	}
}
