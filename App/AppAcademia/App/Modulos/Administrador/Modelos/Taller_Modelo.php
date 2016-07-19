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
    public function CosultaPeriodo(){
        $Consulta = new NeuralBDConsultas(APP);
        $Consulta->Tabla('tbl_periodo');
        $Consulta->Columnas(self::ListarColumnas('tbl_periodo',false,false,APP));
        $Consulta->Condicion("Status = 'ACTIVO'");
        $Consulta->Ordenar('FechaInicio' , 'ASC');
        return $Consulta->Ejecutar(false , true);
    }
    public function CosultaInstructores(){
        $Consulta = new NeuralBDConsultas(APP);
        $Consulta->Columnas("IdInformacion, Nombres, ApellidoPaterno, ApellidoMaterno");
        $Consulta->Tabla('tbl_informacion_usuarios');
        $Consulta->InnerJoin('tbl_sistema_usuarios','tbl_informacion_usuarios.IdInformacion','tbl_sistema_usuarios.IdUsuario');
        $Consulta->InnerJoin('tbl_sistema_usuarios_perfil','tbl_sistema_usuarios.IdPerfil','tbl_sistema_usuarios_perfil.IdPerfil');
        $Consulta->Condicion("tbl_sistema_usuarios_perfil.Status = 'ACTIVO'");
        $Consulta->Condicion("tbl_sistema_usuarios_perfil.Instructor = 'true'");
        return $Consulta->Ejecutar(false , true);
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
    public function DesativarTalleres($IdTaller = false){
        if($IdTaller == true and $IdTaller != ""){
            $SQL = new NeuralBDGab(APP, 'tbl_Talleres');
            $SQL->Sentencia('Status', 'DESACTIVADO');
            $SQL->Condicion('IdTaller', $IdTaller);
            $SQL->Actualizar();
        }
    }
}