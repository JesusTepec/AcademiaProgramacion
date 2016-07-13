<?php

	/**
	 * Controlador: Index
	 */
	class Index extends Controlador {

		/**
		 * Metodo: Constructor
		 */
		function __Construct() {
			parent::__Construct();
			AppSession::ValSessionGlobal();
		}

		/**
		 * Metodo Publico
		 * Index()
		 *
		 * Redirecciona segun el perfil
		 *
		 */
		public function Index() {
			//Ayudas::print_r($_SESSION);
			//Ayudas::print_r(AppSession::InfomacionSession());
			//exit;

			// -- Redireccionamiento por perfil
			$Usuario = AppSession::InfomacionSession();
			if($Usuario['Permiso']['Nombre'] == 'Administrador'){
				unset($Usuario);
				header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Administrador'));
				exit();
			}
			elseif($Usuario['Permiso']['Nombre'] == 'Supervisor'){
				unset($Usuario);
				header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Supervisor'));
				exit();
			}
			
			elseif($Usuario['Permiso']['Nombre'] == 'Agente'){
				unset($Usuario);
				header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Agente'));
				exit();
			}

		}
	}