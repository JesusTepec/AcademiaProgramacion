<?php

	class AppSession {
		
		private static $_Llave = 'wqpmrie8b1z9gyjirnqan2tc1qvc';
		private static $_Contenedor = false;
		private static $_TIEMPO_LOGIN = 3600;
		
		/**
		 * Metodo Publico
		 * Registrar($Usuario = false, $Permiso = false)
		 * 
		 * Registra informacion de una nueva session de trabajo
		 * @param $Usuario: Arreglo de los datos del usuario
		 * @param $Permiso: Arreglo de permisos de usuario array('Central' => 'true', 'Error' => 'true', 'Empresa' => 'false')
		 * 
		 * */
		public static function Registrar($Usuario = false, $Permiso = false){
			$RegistroSession = array(
				'Session' => array(
					'Llave' => implode('_', array(self::$_Llave, $Usuario['Usuario'], date("Y-m-d"))),
					'Fecha' => date("Y-m-d"),
					'Base'  => strtotime(date("Y-m-d H:i:s"))
				),
				'Informacion' => $Usuario,
				'Permiso' => $Permiso
			);
			NeuralSesiones::AsignarSession('UOAUTH_APP', $RegistroSession);
		}
		
		/**
		 * Metodo Publico
		 * ValSessionGlobal()
		 * 
		 * Valida el acceso al metodo mediante los permisos de la session
		 * 
		 **/
		public static function ValSessionGlobal(){
			NeuralSesiones::Inicializar(APP);
			$ModRewrite = \Neural\WorkSpace\Miscelaneos::LeerModReWrite();
			$Control = (isset($ModRewrite[1]) == true) ? $ModRewrite[1] : 'Index';
			$Session = self::LeerSession();
			if(($Session['Session']['Llave'] == implode('_', array(self::$_Llave, $Session['Informacion']['Usuario'], date("Y-m-d")))) == true AND ((($Session['Session']['Base']) + self::$_TIEMPO_LOGIN) > strtotime(date("Y-m-d H:i:s"))) == true){
				if(array_key_exists($Control, $Session['Permiso']) == true){
					if($Session['Permiso'][$Control] == 'false' ){
						header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Error', 'SinAcceso', 'Index'));
						exit();
					}
				}
				else {
					header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Error', 'SinAcceso', 'Index'));
					exit();
				}
			}
			else {
				self::RedireccionLogOut();
				exit();
				// Enviar a un logout y redireccionar con parametro de error por dato get
			}
		}
		
		/**
		 * Metodo Publico
		 * InfomacionSession()
		 * 
		 * Entrega los datos de sesion para ver visualizados
		 * 
		 * */
		public static function InfomacionSession(){
			return self::$_Contenedor;
		}
		
		/**
		 * Metodo Privado
		 * LeerSession()
		 * 
		 * Lee la informacion de la session
		 * 
		 * */
		private static function LeerSession(){
			if(is_array(self::$_Contenedor) == true) {
				return self::$_Contenedor;
			}
			else {
				return self::$_Contenedor = NeuralSesiones::ObtenerSession('UOAUTH_APP');				
			}
		}
		
		/**
		 * Metodo Privado
		 * RedireccionLogOut()
		 * 
		 * LogOut del sistema
		 * 
		 * */
		private static function RedireccionLogOut(){
			header("Location: ".NeuralRutasApp::RutaUrlApp('LogOut'));
			exit();
		}
		
	}