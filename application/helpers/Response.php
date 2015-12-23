<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    class Response{
        
        var $response = array();
        
        function Response(){
            
        }
        
        /**
         * 
         * @param type $codigo Indica el codigo de respuesta del servidor
         */
        function setCodigo($codigo){
            $this->response['codigo'] = $codigo;
        }
        
        /**
         * Obtiene el codigo de respuesta del servidor
         * @return type 
         */
        function getCodigo(){
            return $this->response['codigo'];
        }
        
        /**
         * 
         * @param type $message Indica el mensaje que dará el servidor
         */
        function setMensaje($message){
            $this->response['mensaje'] = $message;
        }
        
        /**
         * Obtiene el mensaje de respuesta del servidor
         * @return type Indica
         */
        function getMensaje(){
            return $this->response['mensaje'];
        }
        
        
    }

?>