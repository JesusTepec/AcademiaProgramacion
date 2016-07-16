<?php

/**
 * Clase: Index_Modelo
 */
class Taller_Modelo extends AppSQLConsultas {

    /**
     * Metodo: Constructor
     */
    function __Construct() {
        parent::__Construct();
    }

    /**
     * Metodo: Ejemplo
     */
    public function ConsultarTalleres() {
        $Consulta = new NeuralBDConsultas(APP);
        $Consulta->Tabla('tbl_talleres');
        $Consulta->Columnas(self::ListarColumnas('tbl_talleres',false, false, APP));
        $Consulta->Condicion("Status = 'ACTIVO'");
        $Consulta->Ordenar('IdTaller', 'DESC');
        return $Consulta->Ejecutar(false, true);
    }
}