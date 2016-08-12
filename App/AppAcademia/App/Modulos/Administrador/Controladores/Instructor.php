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
         * Metodo publico
         * ListarTalleresInstructor()
         *
         * Genera una vista donde se ven los talleres asociados al instructor
         */

        public function ListarTalleresInstructor(){
            if(isset($_POST['IdUsuario']) AND $_POST['IdUsuario'] != "") {
                $Datos = $this->Modelo->ListarTalleresInstructor(NeuralCriptografia::DeCodificar($_POST['IdUsuario'], APP));
                $Plantilla = new NeuralPlantillasTwig(APP);
                $Plantilla->Filtro('Cifrado',function($parametros){return NeuralCriptografia::Codificar($parametros);});
                $Plantilla->Parametro('TalleresAsociados', $Datos);
                echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('Instructor', 'Listado', 'ListaTalleres.html')));
                unset($Datos,$Plantilla);
            }
        }

        /**
         * Metodo publico
         * ListarAsistentesTaller
         *
         * Genera una vista de los asistentes asociados a un Taller
         */
        
        public function ListarAsistentesTaller(){
            if(isset($_POST['IdTaller']) AND $_POST['IdTaller'] != "") {
                $Datos = $this->Modelo->ListarAsistentesTaller(NeuralCriptografia::DeCodificar($_POST['IdTaller'],APP));
                $Plantilla = new NeuralPlantillasTwig(APP);
                $Plantilla->Filtro('Cifrado',function($parametros){return NeuralCriptografia::Codificar($parametros);});
                $Plantilla->Parametro('ListaAsistentes', $Datos);
                echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('Instructor', 'Listado', 'ListaAsistentes.html')));
                unset($Datos, $Plantilla);
            }
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
         * eliminarInstructor()
         *
         * Recibe el id de un Instructor para posteriormente invocar al metodo
         * del modelo el cual cambia el status de el instructor asociado al id
         */
        public function EliminarInstructor(){
            if($_POST['IdUsuario']== true AND $_POST['IdUsuario'] != "" AND isset($_POST['IdUsuario'])) {
                if(is_numeric(NeuralCriptografia::DeCodificar($_POST['IdUsuario'],APP))){
                    $this->Modelo->EliminarInstructor(NeuralCriptografia::DeCodificar($_POST['IdUsuario']));
                }                
            }
        }

    }