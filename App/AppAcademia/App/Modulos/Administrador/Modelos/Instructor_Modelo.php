<?php

/**
 * Clase: Instructor_Modelo
 */
class Instructor_Modelo extends AppSQLConsultas {

    /**
     * Metodo: Constructor
     */
    function __Construct() {
        parent::__Construct();
    }

    /**
     * Metodo Publico
     * ConsultarInstructores()
     *
     * Consulta y retorna a los usuarios de perfil Instructor y que aparte estos esten activos 
     * dentro de la Base de Datos
     */
    public function ConsultarInstructores() {
        $Consulta = new NeuralBDConsultas(APP);
        $Consulta->Tabla('tbl_sistema_usuarios');
        $Consulta->Columnas("tbl_sistema_usuarios.IdUsuario,Nombres,ApellidoPaterno,ApellidoMaterno,TelefonoMovil1,Correo,tbl_informacion_usuarios.Status");
        $Consulta->InnerJoin('tbl_informacion_usuarios', 'tbl_sistema_usuarios.IdUsuario', 'tbl_informacion_usuarios.IdUsuario');
        $Consulta->InnerJoin('tbl_sistema_usuarios_perfil', 'tbl_sistema_usuarios.IdPerfil', 'tbl_sistema_usuarios_perfil.IdPerfil');
        $Consulta->Condicion("tbl_sistema_usuarios_perfil.IdPerfil = '2'");
        $Consulta->Condicion("tbl_sistema_usuarios.Status = 'Activo'");
        return $Consulta->Ejecutar(false,true);
    }
    
    /**
     * Metodo Publico
     * ConsultarAsistentes()
     *
     * Consulta y retorna a los usuarios con el perfil 3 es decir 'asistentes' y que aparte estos esten activos 
     * dentro de la Base de Datos
     */
    public function ConsultarAsistentes(){
        $Consulta=new NeuralBDConsultas(APP);
        $Consulta->Tabla('tbl_informacion_usuarios');
        $Consulta->Columnas("tbl_informacion_usuarios.IdUsuario,CONCAT(tbl_informacion_usuarios.Nombres,' ',tbl_informacion_usuarios.ApellidoPaterno,' ',tbl_informacion_usuarios.ApellidoMaterno) AS Nombres");
        $Consulta->InnerJoin('tbl_sistema_usuarios','tbl_informacion_usuarios.IdUsuario','tbl_sistema_usuarios.IdUsuario');
        $Consulta->Condicion('tbl_sistema_usuarios.IdPerfil=3');
        return $Consulta->Ejecutar(false,true);
    }

    /**
     * @param bool $IdUsuario
     * @return array
     *
     * Metodo Publico
     * ListarTalleresInstructor();
     * Recibe el Id de un usuario seleccionado de la vista de instructores
     * para posteriormente consultar los talleres asociados a el
     */

    public function ListarTalleresInstructor($IdUsuario = false){
        if(isset($IdUsuario) == true AND $IdUsuario != ""){
            $Consulta=new NeuralBDConsultas(APP);
            $Consulta->Tabla('tbl_talleres');
            $Consulta->Columnas(implode(',', self::ListarColumnas('tbl_talleres', false, false, APP)));
            $Consulta->InnerJoin('tbl_instructores_talleres', 'tbl_talleres.IdTaller', 'tbl_instructores_talleres.IdTaller');
            $Consulta->InnerJoin('tbl_informacion_usuarios', 'tbl_instructores_talleres.IdInformacionInstructor', 'tbl_informacion_usuarios.IdInformacion');
            $Consulta->InnerJoin('tbl_sistema_usuarios', 'tbl_informacion_usuarios.IdUsuario', 'tbl_sistema_usuarios.IdUsuario');
            $Consulta->Condicion("tbl_sistema_usuarios.IdUsuario = '$IdUsuario'");
            return $Consulta->Ejecutar(false,true);
        }
    }

    /**
     * Metodo Publico
     * ConvertirAsistenteInstructor($IdUsuario = false)
     *
     * Cambia el perfil 1 de un usario 'asistente' a el perfil 2, es decir a Instructor,
     * para esto recibe el ID del usuario a convertir
     * @param $IdUsuario
     */
    public function ConvertirAsistenteInstructor($IdUsuario = false){
        if($IdUsuario == true and $IdUsuario != ""){
            $SQL = new NeuralBDGab(APP, 'tbl_sistema_usuarios');
            $SQL->Sentencia('IdPerfil', '2');
            $SQL->Condicion('IdUsuario', $IdUsuario);
            $SQL->Actualizar();
        }
    }

    /**
     * @param bool $IdUsuario
     * Metodo Publico
     *  Elimina definitivamente a un Instructor segun el ide
     * que tenga asociado
     */

    public function EliminarInstructor($IdUsuario = false){
        if($IdUsuario == true and $IdUsuario != ""){
            $SQL = new NeuralBDGab(APP, 'tbl_sistema_usuarios');
            $SQL->Sentencia('Status', 'ELIMINADO');
            $SQL->Condicion('IdUsuario', $IdUsuario);
            $SQL->Actualizar();
        }
    }
    
    /**
     * @param array $Array
     * Metodo Publico InsertarUsuario
     * Recibe los datos en un arreglo para registrar un usuario
     * */  
    public function InsertarUsuario($Array = false){
        $SQL = new NeuralBDGab(APP, 'tbl_sistema_usuarios');
        $SQL->Sentencia('IdPerfil','2');
        $SQL->Sentencia('Usuario', $Array['Usuario']);
        $SQL->Sentencia('Password',hash('sha256',$Array['Password']));
        $SQL->Insertar();
    }
    
    /**
     * @param array $Array
     * Metodo Publico BuscarUsuario
     * Recibe los datos en un arreglo para buscar si un usuario esta registrado y devuelve el Id
     * */  
    public function BuscarUsuario($Array=false){
        $Consulta = new NeuralBDConsultas(APP);
        $Consulta->Tabla('tbl_sistema_usuarios');
        $Consulta->Columnas("tbl_sistema_usuarios.IdUsuario");
        $Consulta->Condicion("tbl_sistema_usuarios.Usuario = '".$Array['Usuario']."'");
        return $Consulta->Ejecutar(false,true);
    }
    
    /**
     * @param array $Array, interger $IdUsuario
     * Metodo Publico InsertarInformacionUsuario
     * Registra la informacion de un usuario y lo asocia a su Id
     * */ 
    public function InsertarInformacionUsuario($Array = false,$IdUsuario= false){
        $SQL = new NeuralBDGab(APP, 'tbl_informacion_usuarios');
        $SQL->Sentencia('IdUsuario',$IdUsuario);
        $SQL->Sentencia('Nombres',$Array['Nombres']);
        $SQL->Sentencia('ApellidoPaterno',$Array['ApellidoPaterno']);
        $SQL->Sentencia('ApellidoMaterno',$Array['ApellidoMaterno']);
        $SQL->Sentencia('Cargo',$Array['Cargo']);
        $SQL->Sentencia('Direccion',$Array['Direccion']);
        $SQL->Sentencia('Correo',$Array['Correo']);
        $SQL->Sentencia('TelefonoFijo1',$Array['Telefono']);
        $SQL->Sentencia('TelefonoMovil1',$Array['Celular']);
        $SQL->Insertar();
    }



}