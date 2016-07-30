<?php

    class Instructor extends Controlador{

        var $Informacion;

        function __Construct() {
            parent::__Construct();
            AppSession::ValSessionGlobal();
            $this->Informacion = AppSession::InfomacionSession();
        }

        /**
         * Metodo publico
         * Index
         * Llamado de la base del html
         *
         */
        public function Index(){
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
            echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('Instructor', 'Index.html')));
            unset($Menu, $MenuSeleccion, $TipoUsuario, $Usuario, $Validacion, $Plantilla);
            exit();

        }

        /**
         * Metodo publico
         * ConsultarInstructores
         * Realiza la consulta de los instructores activos
         *
         */
        public function ConsultarInstructores(){
            $Datos = $this->Modelo->ConsultarInstructores();     
            $Plantilla = new NeuralPlantillasTwig(APP);
            $Plantilla->Filtro('Cifrado',function($parametros){return NeuralCriptografia::Codificar($parametros);});
            $Plantilla->Parametro('Datos',$Datos);
            echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('Instructor','Listado','ListaInstructores.html')));
            unset($Datos,$Plantilla);
        }
        
        /**
         * M�todo publico
         * frmAgregar()
         * 
         * Muestra una lista de asistentes activos que pueden ser instructores
         * */
        public function frmAgregar(){
            $ConsultaAsitentes = $this->Modelo->ConsultarAsistentes();
            for($i=0;$i<count($ConsultaAsitentes);$i++){
                $ConsultaAsitentes[$i]['IdUsuario']=NeuralCriptografia::Codificar($ConsultaAsitentes[$i]['IdUsuario'],APP);
            }            
            $Plantilla = new NeuralPlantillasTwig(APP);
            $Plantilla->Parametro('DatosAsistentes', $ConsultaAsitentes);
            echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('Instructor', 'Agregar', 'AgregarInstructor.html')));
            unset($ConsultaAsitentes,$Plantilla);
        }
        
        /**
         * M�todo publico
         * ConvertirInstructor()
         * 
         * Recibe el Id de un asistente, el cual se convertir� en instructor
         * */
        public function ConvertirInstructor(){
            if($_POST['IdUsuario']== true AND $_POST['IdUsuario'] != "" AND isset($_POST['IdUsuario'])) {
                if(is_numeric(NeuralCriptografia::DeCodificar($_POST['IdUsuario'],APP))){
                    $this->Modelo->ConvertirAsistenteInstructor(NeuralCriptografia::DeCodificar($_POST['IdUsuario']));
                }                
            }
        }

        /**
         * Metodo publico
         * CambiarPerfil()
         *
         * Recibe el Id de un Instructor, invoca al metodo del modelo
         * donde se realiza el update a sólo asistente
         */
        public function CambiarPerfil(){
            if($_POST['IdUsuario']== true AND $_POST['IdUsuario'] != "" AND isset($_POST['IdUsuario'])) {
                if(is_numeric(NeuralCriptografia::DeCodificar($_POST['IdUsuario'],APP))){
                    $this->Modelo->CambiaPerfil(NeuralCriptografia::DeCodificar($_POST['IdUsuario']));
                }                
            }
        }

    }