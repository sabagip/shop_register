<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');
require(APPPATH . '/libraries/Sanitize.php');

class Login extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->library('Sanitize');
        
        $this->load->helper('Response');
    }

	
    public function logueo_get(){

        $user = $this->sanitize->clean_string( $this->get('usuario') );
        $pass = $this->sanitize->clean_string( $this->get('pass') );
        $respuesta = new Response();

        if($user == NULL ):
            $respuesta->setCodigo(400);
            $respuesta->setMensaje("Usuario vacio");
            $this->response($respuesta->getMensaje(), $respuesta->getCodigo());
        endif;

        $response = $this->m_consultas->login($user, $pass);
        
        if($response == FALSE):
            $respuesta->setCodigo(400);
            $respuesta->setMensaje("userPassFail");
            $this->response($respuesta->getMensaje(), $respuesta->getCodigo());
        endif;
        
        $respuesta->setMensaje($response);
        $respuesta->setCodigo(200);

        $this->response($respuesta);
    }
}
