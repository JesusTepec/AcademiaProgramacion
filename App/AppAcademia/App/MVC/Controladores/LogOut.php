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
			header("Location: ".NeuralRutasApp::RutaUrlApp('Index'));
			exit();
		}
	}