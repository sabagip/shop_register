<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH.'libraries/Sanitize.php');

class Login extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('response');
        $this->load->library(array('Sanitize'));
        
    }

	
    public function logueo_get(){
        
        $sanador = new Sanitize();
        
        $user =  $sanador->clean_string( $this->get('usuario') );
        $pass =  $sanador->clean_string( $this->get('pass') );

        $response = $this->m_consultas->login($user, $pass);
        
        if($response == FALSE):
            $respuesta = new Response(400, "userPassFail");
            $this->response($respuesta);
        endif;
        
        $respuesta = new Response(200, $response);       

        $this->response($respuesta);
    }
}
