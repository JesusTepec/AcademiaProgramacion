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
         * ConsultarTalleres
         * Realiza la consulta de los talleres activos
         *
         */
        public function ConsultarInstructores(){
            $Datos = $this->Modelo->ConsultarInstructores();
            $Plantilla = new NeuralPlantillasTwig(APP);
            $Plantilla->Parametro('Datos',$Datos);
            echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('Instructor','Listado','ListaTaller.html')));
        }



    }