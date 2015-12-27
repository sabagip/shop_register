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
    
    /**
     * 
     * @param integer $idUsuario Indica el id del usuario
     * @param integer $dia 1 = primer quincena del mes, 2 = segunda quincena del mes
     * @param type $mes = Indica el mes actual
     * @param type $ano = Indica el año actual
     * @param type $ultimoDiaDelMes Se manda cuando es la segunda quincena del mes
     * @return integer devuelve el total gastado por el usuario
     */
    function totalGastado($idUsuario = 0, $dia = 0, $mes = 0, $ano = 0, $ultimoDiaDelMes = 0){
        
        $this->db
                ->select('idCompra, cantidad, fecha')
                ->from('shop_compra')
                ->where('idUser', $idUsuario)
                ->order_by('fecha');
        
        if($dia == 1):
            $this->db->where('fecha >=', $ano ."-" . $mes . "-1" );
            $this->db->where('fecha <= ', $ano . "-" - $mes . "-" . $dia );
        
        else:
            $this->db->where('fecha >= ', $ano ."-" . $mes . "-15" );
            $this->db->where('fecha <= ', $ano . "-" . $mes . "-" . $ultimoDiaDelMes );
        endif;
        
        
        $result = $this->db->get();
        //echo "<pre>"; print_r($this->db->last_query()); die;
        if($result->num_rows() >= 1):
            return $result->result();
        else:
            return FALSE;
        endif;
    }
    
    /**
     * 
     * @param integer $idUsuario Indica el id del usuario
     * @param integer $dia 1 = primer quincena del mes, 2 = segunda quincena del mes
     * @param type $mes = Indica el mes actual
     * @param type $ano = Indica el año actual
     * @param type $ultimoDiaDelMes Se manda cuando es la segunda quincena del mes
     * @return integer devuelve el total gastado por el usuario
     */
    function totalIngresado($idUsuario = 0, $dia = 0, $mes = 0, $ano = 0, $ultimoDiaDelMes = 0){
        $this->db
                ->select('idIngreso, cantidad, fecha')
                ->from('shop_ingreso')
                ->where('idUser', $idUsuario);
        
        if($dia == 1):
            $this->db->where('fecha >=', $ano ."-" . $mes . "-1" );
            $this->db->where('fecha <= ', $ano . "-" - $mes . "-" . $dia );
        
        else:
            $this->db->where('fecha >= ', $ano ."-" . $mes . "-15" );
            $this->db->where('fecha <= ', $ano . "-" . $mes . "-" . $ultimoDiaDelMes );
        endif;
        
        $result = $this->db->get();
        //echo "<pre>"; print_r($this->db->last_query()); die;
        if($result->num_rows() >= 1):
            return $result->result();
        else:
            return FALSE;
        endif;
    } 
    
    /**
     * 
     * @param integer $idUsuario Indica el id del usuario
     * @param integer $dia 1 = primer quincena del mes, 2 = segunda quincena del mes
     * @param type $mes = Indica el mes actual
     * @param type $ano = Indica el año actual
     * @param type $ultimoDiaDelMes Se manda cuando es la segunda quincena del mes
     * @return integer devuelve el total gastado por el usuario
     */
    function totalAhorro($idUsuario = 0, $dia = 0, $mes = 0, $ano = 0, $ultimoDiaDelMes = 0){
        $this->db
                ->select('idAhorro, cantidad, fecha')
                ->from('shop_ahorro')
                ->where('idUser', $idUsuario);
        
        if($dia == 1):
            $this->db->where('fecha >=', $ano ."-" . $mes . "-1" );
            $this->db->where('fecha <= ', $ano . "-" - $mes . "-" . $dia );
        
        else:
            $this->db->where('fecha >= ', $ano ."-" . $mes . "-15" );
            $this->db->where('fecha <= ', $ano . "-" . $mes . "-" . $ultimoDiaDelMes );
        endif;
        
        $result = $this->db->get();
        //echo "<pre>"; print_r($this->db->last_query()); die;
        if($result->num_rows() >= 1):
            return $result->result();
        else:
            return FALSE;
        endif;
    } 
    
    function totalAGastar($idUsuario = 0){
        $this->db
                ->select('cantidad')
                ->from('shop_a_gastar')
                ->where('idUsuario', $idUsuario);
        
        $result = $this->db->get();
        return $result->result();
    }
}
