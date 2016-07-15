<?php

	class LogOut extends Controlador {

		/**
		 * Metodo: Constructor
		 */
		function __Construct() {
			parent::__Construct();
			NeuralSesiones::Inicializar(APP);
			NeuralSesiones::Finalizar(true);
			header("Location: ".NeuralRutasApp::RutaUrlApp('Index'));
			exit();
		}

		/**
		 * Metodo: Index
		 */
		public function Index() {
			NeuralSesiones::Finalizar($_SESSION['UOAUTH_APP']);
			header("Location: ".NeuralRutasApp::RutaUrlApp('Index'));
			exit();
		}
	}