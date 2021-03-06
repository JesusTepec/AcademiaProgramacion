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
        $Plantilla->Filtro('Cifrado',function($parametros){return NeuralCriptografia::Codificar($parametros);});
        echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('Taller', 'Listado', 'Listado.html')));
        unset($Consulta,$Plantilla);
    }
    /**
     * Metodo Publico
     * Desactivar()
     *
     * Desactiva un taller para que este ya no se siga mostrando en la Tabla
     *
     */
    public function Desactivar(){
        if($_POST['IdTaller']== true and $_POST['IdTaller'] != "") {
            //validar existencia, no vacio, cifrar el id, y decifrar
            $this->Modelo->DesativarTalleres(NeuralCriptografia::DeCodificar($_POST['IdTaller']));
        }
    }

    /**
     * Metodo Publico
     * CargarCmb()
     *
     * muestra la vista para agregar con informacion de periodo y instructores
     *
     * @throws NeuralException
     */
    public function CargarCmb(){
        $ConsultaPeriodo = $this->Modelo->ConsultaPeriodo();
        $ConsultaInstructores = $this->Modelo->ConsultaInstructores();
        $Plantilla = new NeuralPlantillasTwig(APP);
        $Plantilla->Parametro('DatosPeriodo', $ConsultaPeriodo);
        $Plantilla->Parametro('DatosInstructores', $ConsultaInstructores);
        $Plantilla->Filtro('Cifrado',function($parametros){return NeuralCriptografia::Codificar($parametros);});
        $Plantilla->Parametro('Key',NeuralCriptografia::Codificar(AppFechas::ObtenerFechaActual(), APP));
        echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('Taller', 'Agregar', 'frmAgregar.html')));
        unset($Consulta,$Plantilla);
    }

    /**
     * Metodo Publico
     * frmAgregarTaller()
     *
     * Agregamos un nuevo taller
     */
    public function frmAgregarTaller()
    {
        
        if (isset($_POST) and NeuralCriptografia::DeCodificar($_POST['Key'], APP) == AppFechas::ObtenerFechaActual()) {
            unset($_POST['Key']);
            $this->Modelo->AgregarTaller(NeuralCriptografia::DeCodificar($_POST['IdPeriodo']),NeuralCriptografia::DeCodificar($_POST['IdInformacion']),$_POST);
            $this->Listar();
        }
    }

    }