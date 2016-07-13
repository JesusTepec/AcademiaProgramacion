<?php

	class SinAutorizacion extends Controlador {

		function __Construct() {
			parent::__Construct();
		}

		/**
		 * Metodo Publico
		 * Index()
		 *
		 * Mustra la pantalla de error sin acceso
		 *
		 */
		public function Index() {
			$Plantilla = new NeuralPlantillasTwig(APP);
			echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('SinAutorizacion', 'NoExisteUsuario.html')));
			unset($Plantilla);
			exit();
		}

		/**
		 * Metodo Publico
		 * Supendido()
		 *
		 * Muestra la pantalla de usuario supendido
		 *
		 * */
		public function Supendido() {
			$Plantilla = new NeuralPlantillasTwig(APP);
			echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('SinAutorizacion', 'UsuarioSuspendido.html')));
			unset($Plantilla);
			exit();
		}


	}