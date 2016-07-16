<?php

/**
 * Clase: Instructor_Modelo
 */
class Instructor_Modelo extends Modelo {

    /**
     * Metodo: Constructor
     */
    function __Construct() {
        parent::__Construct();
    }

    /**
     * Metodo: Ejemplo
     */
    public function ConsultarInstructores() {
        $Consulta = new NeuralBDConsultas(APP);
        $Consulta->Tabla('tbl_sistema_usuarios');
        $Consulta->Columnas("Nombres,ApellidoPaterno,TelefonoMovil1,tbl_informacion_usuarios.Status");
        $Consulta->InnerJoin('tbl_informacion_usuarios', 'tbl_sistema_usuarios.IdUsuario', 'tbl_informacion_usuarios.IdUsuario');
        $Consulta->InnerJoin('tbl_sistema_usuarios_perfil', 'tbl_sistema_usuarios.IdPerfil', 'tbl_sistema_usuarios_perfil.IdPerfil');
        $Consulta->Condicion("tbl_sistema_usuarios_perfil.Nombre = 'Instructor'");
        $Consulta->Condicion("tbl_sistema_usuarios.Status = 'Activo'");
        return $Consulta->Ejecutar(false,true);
    }
}