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
        $Consulta->Columnas("tbl_periodo.IdPeriodo, tbl_periodo.Nombre As nmPeriodo, tbl_talleres.IdTaller, tbl_talleres.Nombre, tbl_talleres.Descripcion, tbl_talleres.Horario, tbl_talleres.Lugar, tbl_talleres.FechaCreacion, tbl_talleres.Status");
        $Consulta->InnerJoin('tbl_periodo', 'tbl_talleres.IdPeriodo','tbl_periodo.IdPeriodo');
        $Consulta->Condicion("tbl_talleres.Status = 'ACTIVO'");
        $Consulta->Ordenar('tbl_talleres.FechaCreacion' , 'DESC');
        return $Consulta->Ejecutar(false , true);
    }

    /**
     * Metodo Publico
     * ConsultaPeriodo
     *
     * Lista los registros de periodos Activos
     * @return array
     */
    public function ConsultaPeriodo(){
        $Consulta = new NeuralBDConsultas(APP);
        $Consulta->Tabla('tbl_periodo');
        $Consulta->Columnas(self::ListarColumnas('tbl_periodo',false,false,APP));
        $Consulta->Condicion("Status = 'ACTIVO'");
        $Consulta->Ordenar('FechaInicio' , 'ASC');
        return $Consulta->Ejecutar(false , true);
    }

    /**
     * Metodo Publico
     *ConsultaInstructores()
     *
     * Lista la informacion de los Instructores Activos
     * @return array
     */
    public function ConsultaInstructores(){
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

    /**
     * Metodo Publico
     * ActualizarTaller()
     *
     * Actualizamos los campos del registro seleccionado
     *
     * @param bool $IdTaller
     * @param bool $Nombre
     * @param bool $Descripcion
     * @param bool $Horario
     * @param bool $Lugar
     * @throws NeuralException
     */
    public function ActualizarTaller($IdTaller = false, $Nombre = false, $Descripcion = false, $Horario = false, $Lugar = false){
        if($IdTaller == true and $IdTaller != "" and $Nombre == true and $Nombre != "" and $Descripcion == true and $Descripcion != "" and $Horario == true and $Horario != "" and $Lugar == true and $Lugar != ""){
            $SQL = new NeuralBDGab(APP, 'tbl_Talleres');
            $SQL->Sentencia('Nombre', $Nombre);
            $SQL->Sentencia('Descripcion', $Descripcion);
            $SQL->Sentencia('Horario', $Horario);
            $SQL->Sentencia('Lugar', $Lugar);
            $SQL->Condicion('IdTaller', $IdTaller);
            $SQL->Actualizar();
        }
    }
}