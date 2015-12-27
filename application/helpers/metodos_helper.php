<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function ultimoDiaDelMes($month = 0, $year = 0){
        
        $day = date("d", mktime(0,0,0, $month+1, 0, $year));
        return $day;
    }
    
    function sumaDetallado($cantidades){
        $total = 0;
        if($cantidades != FALSE):
            foreach ($cantidades as $columna):
                $total += $columna->cantidad;
            endforeach;
        endif;
        return $total;
    }
    
    
    
?>
