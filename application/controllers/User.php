<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('America/Chihuahua');
require(APPPATH . '/libraries/REST_Controller.php');
require(APPPATH . '/libraries/Sanitize.php');

class User extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->library('Sanitize');
        //$this->load->helper(array('Response', 'Metodos'));
    }

	
    public function detalleIndex_get(){
        
        $sanador = new Sanitize();
        
        $user = $sanador->clean_string($this->get('usuario'));
        $validacion = $this->validaUsuario($user);
        
        if($validacion):
            $respuesta = $this->llenaIndex($user);
        else:
            $respuesta = new Response(400, "withOutUser");
        endif;
        
        $this->response($respuesta);

    }

    private function validaUsuario($idUsuario){
        if(isset($idUsuario)):
            return TRUE;
        else:
            return FALSE;
        endif;
    }
    
    private function llenaIndex($idUsuario){
        
        $dia = $this->getInicioQuincena();
        $mes = date('m');
        $ano = date('Y');
        $data = array();
        
        if($dia == 1):
            $data['gastado'] = $this->m_consultas->totalGastado($idUsuario, $dia, $mes, $ano);
            $data['ingreso'] = $this->m_consultas->totalIngresado($idUsuario, $dia, $mes, $ano);
            $data['ahorro'] = $this->m_consultas->totalAhorro($idUsuario, $dia, $mes, $ano);
        else:
            $ultimoDiaDelMes = ultimoDiaDelMes(date('m'), date('Y'));
            $data['gastado'] = $this->m_consultas->totalGastado($idUsuario, $dia, $mes, $ano, $ultimoDiaDelMes);    
            $data['ingreso'] = $this->m_consultas->totalIngresado($idUsuario, $dia, $mes, $ano, $ultimoDiaDelMes);
            $data['ahorro'] = $this->m_consultas->totalAhorro($idUsuario, $dia, $mes, $ano, $ultimoDiaDelMes);
        endif;
        
        $data['aGastar'] = $this->m_consultas->totalAGastar($idUsuario);
        
        $data['gastado']['total'] = sumaDetallado($data['gastado']);
        $data['ingreso']['total'] = sumaDetallado($data['ingreso']);
        $data['ahorro']['total'] = sumaDetallado($data['ahorro']);
        $data['restanteCorriente'] =  $data['aGastar'][0]->cantidad - $data['gastado']['total'];
        $data['restanteAhorro'] = $data['ahorro']['total'] + $data['restanteCorriente'];
        $data['restanteTotal'] = $data['ingreso']['total'] - $data['gastado']['total'];
                
        $respuesta = new Response(200, $data);
        
        $this->response($respuesta);
    }
    
    private function getInicioQuincena(){
        
        $dia = date('d');
        
        if($dia >= 15 && $dia <= 31):
            return 2; // Indica la segunda quincena
        elseif($dia >= 1 && $dia < 15):    
            return 1;   // Indica la primera quincena
        endif;
    }
}
