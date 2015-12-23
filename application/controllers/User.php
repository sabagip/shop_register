<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');
require(APPPATH . '/libraries/Sanitize.php');

class User extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->library('Sanitize');
        $this->load->helper('Response');
    }

	
    public function detalle_get(){

        $user = $this->sanitize->clean_string($this->get('usuario'));
        $validacion = $this->validaUsuario($user);
        $this->response('hola', 200);

    }

    private function validaUsuario($idUsuario){
        if(isset($idUsuario)):
            return TRUE;
        else:
            return FALSE;
        endif;
    }
}
