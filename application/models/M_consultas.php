<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of m_consultas
 *
 * @author sergio
 */
class M_consultas extends CI_Model {
    
    function login($usuario, $pass){
        
        
        $this->db
                ->select('idUser', 'usuario')
                ->from('shop_user')
                ->where('usuario = "' . $usuario . '"');
                
        
        if($pass === ""):
            $this->db->where('contrasena IS NULL ');
        endif;
        
        if($pass != ""):
            $this->db->where('contrasena = "' . $pass . '"');
        endif;
        
        $result = $this->db->get();
        
        if($result->num_rows() == 1):
            return $result->result();
        else:
            return FALSE;
        endif;
    }
}
