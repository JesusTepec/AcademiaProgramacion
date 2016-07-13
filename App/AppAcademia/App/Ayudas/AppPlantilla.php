<?php

	class AppPlantilla {
		
		/**
		 * Metodo Publico
		 * Separador($Datos = false)
		 * 
		 * Arreglo listado de ubicacion de la plantilla
		 * @param $Datos: Arreglo
		 * @return URL Path
		 * 
		 * */
		public static function Separador($Datos = false){
			if($Datos == true AND is_array($Datos)){
				return implode(DIRECTORY_SEPARATOR, $Datos);	
			}
		}
	}