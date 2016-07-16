<?php

class Taller extends Controlador {

    var $Informacion;

    /**
     * Metodo Constructor
     */
    function __Construct() {
        parent::__Construct();
        AppSession::ValSessionGlobal();
        $this->Informacion = AppSession::InfomacionSession();
    }

    /**
     * Metodo Publico
     * Index()
     *
     * Pantalla Principal del sistema
     *
     */
    public function Index() {
        $Menu = \Neural\WorkSpace\Miscelaneos::LeerModReWrite();
        $MenuSeleccion = (isset($Menu[2])) ? $Menu[2] : 'Index';
        $TipoUsuario = $this->Informacion['Permiso']['Nombre'];
        $Usuario = $this->Informacion['Informacion']['Nombres'] . ' ' . $this->Informacion['Informacion']['ApellidoPaterno'];
        $Validacion = new NeuralJQueryFormularioValidacion(true, true, false);
        $Validacion->Requerido('PasswordActual');
        $Validacion->Requerido('PasswordNuevo');
        $Validacion->Requerido('PasswordVerifica');
        $Validacion->CampoIgual('PasswordVerifica', 'PasswordNuevo');
        $Plantilla = new NeuralPlantillasTwig(APP);
        $Plantilla->Parametro('TipoUsuario', $TipoUsuario);
        $Plantilla->Parametro('Menu', $MenuSeleccion);
        $Plantilla->Parametro('Usuario', $Usuario);
        $Plantilla->Parametro('KeyPerfil', NeuralCriptografia::Codificar(date("Y-m-d"), APP));
        $Plantilla->Parametro('ScriptPerfil', $Validacion->Constructor('frmCambioPassword'));
        echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('Taller', 'Index.html')));
        unset($Menu, $MenuSeleccion, $TipoUsuario, $Usuario, $Validacion, $Plantilla);
        exit();
    }

    /**
     * Metodo Publico
     * Listar()
     *
     * Muestra el registro de los talleres guardados
     * @throws NeuralException
     */
    public function Listar(){
        $Consulta = $this->Modelo->ConsultarTalleres();
        $Plantilla = new NeuralPlantillasTwig(APP);
        $Plantilla->Parametro('Datos', $Consulta);
        echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('Taller','Listado', 'Listado.html')));
        unset($Consulta,$Plantilla);
    }

}