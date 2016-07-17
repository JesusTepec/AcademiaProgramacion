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
     * Metodo Publico
     * ConsultarTalleres()
     *
     * Consulta y retorna los talleres activos
     * dentro de la Base de Datos
     */
    public function ConsultarTalleres() {
        $Consulta = new NeuralBDConsultas(APP);
        $Consulta->Tabla('tbl_talleres');
        $Consulta->Columnas(self::ListarColumnas('tbl_talleres',false, false, APP));
        $Consulta->Condicion("Status = 'ACTIVO'");
        $Consulta->Ordenar('IdTaller', 'DESC');
        return $Consulta->Ejecutar(false, true);
    }

    /**
     * Metodo Publico
     * DesactivarTalleres($idTaller)
     *
     * Desactiva el registro de la Base de Datos
     * deacuerdo a su ID para que ya no se muestre
     * @param $idTaller
     * @throws NeuralException
     */
    public function DesativarTalleres($idTaller){
        $SQL = new NeuralBDGab(APP, 'tbl_Talleres');
        $SQL->Sentencia('Status', 'DESACTIVADO');
        $SQL->Condicion('IdTaller', $idTaller);
        $SQL->Actualizar();
    }
}