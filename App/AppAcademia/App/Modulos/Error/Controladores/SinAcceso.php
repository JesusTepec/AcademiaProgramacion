<?php

	class SinAcceso extends Controlador {

		function __Construct(){
			parent::__Construct();
			AppSession::ValSessionGlobal();
		}

		public function Index(){
			$Plantilla = new NeuralPlantillasTwig(APP);
			echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('SinAutorizacion', 'UsuarioSinAcceso.html')));
			unset($Plantilla);
			exit();
		}


	}