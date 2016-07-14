<?php

	class Index extends Controlador {

		/**
		 * Index::__Construct()
		 *
		 * Genera la validacion de la sesion y genera
		 * el importe de la herencia del controlado
		 * @return ok
		 * @return void
		 */
		function __Construct() {
			parent::__Construct();
			NeuralSesiones::Inicializar(APP);
			if(isset($_SESSION, $_SESSION['UOAUTH_APP']) == true){
				header('Location:'.NeuralRutasApp::RutaUrlAppModulo('Central'));
				exit();
			}
		}

		/**
		 * Index::Index()
		 *
		 * Genera la plantilla de login del usuario
		 * @return ok
		 * @return void
		 */
		public function Index() {			//echo hash('sha256', "1234") ;			//exit();
			$Validacion = new NeuralJQueryFormularioValidacion(true, true, false);
			$Validacion->Requerido('Usuario', '* Nombre de usuario requerido');
			$Validacion->Requerido('Password', '* Contraseña requerida');
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Scripts', $Validacion->Constructor('frm_Login'));
			$Plantilla->Parametro('Key', NeuralCriptografia::Codificar(date("Y-m-d"), APP));
			echo $Plantilla->MostrarPlantilla('Principal.html');
			unset($Validacion, $Plantilla);
			exit();
		}

		/**
		 * Genera la vista para logeo al sistema
		 * @throws NeuralException
		 */
		public function Login(){
			$Validacion = new NeuralJQueryFormularioValidacion(true, true, false);
			$Validacion->Requerido('Usuario', '* Nombre de usuario requerido');
			$Validacion->Requerido('Password', '* Contraseña requerida');
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Scripts', $Validacion->Constructor('frm_Login'));
			$Plantilla->Parametro('Key', NeuralCriptografia::Codificar(date("Y-m-d"), APP));
			echo $Plantilla->MostrarPlantilla('Login.html');
			unset($Validacion, $Plantilla);
			exit();
		}

		/**
		 * Index::Autenticacion()
		 *
		 * Genera el proceso de autenticacion
		 * @return void
		 */
		public function Autenticacion() {
			if(isset($_POST) == true AND isset($_POST['Key']) == true AND NeuralCriptografia::DeCodificar($_POST['Key'], APP) == date("Y-m-d")) :
				$this->AutenticacionDatosVacios();
			else:
				exit('No se envio datos para gestionar');
			endif;
		}

		/**
		 * Index::AutenticacionDatosVacios()
		 *
		 * genera la validacion de datos vacios
		 * return ok
		 * @return void
		 */
		private function AutenticacionDatosVacios() {
			if(AppPost::DatosVacios($_POST) == false):
				$this->AutenticacionConsultarUsuario();
			else:
				exit('El formulario tiene datos vacios');
			endif;
		}

		/**
		 * Index::AutenticacionConsultarUsuario()
		 *
		 * Genera la validacion del usuario
		 * @return ok
		 * @return void
		 */
		private function AutenticacionConsultarUsuario() {
			unset($_POST['Key']);
			$DatosPost = AppPost::FormatoEspacio(AppPost::LimpiarInyeccionSQL($_POST));
			$Consulta = $this->Modelo->ConsultarUsuario($DatosPost['Usuario'], hash('sha256', $DatosPost['Password']));
			if($Consulta['Cantidad'] == 1):
				$this->AutenticacionConsultaPermisos($Consulta);
			else:
				$this->AutenticacionNoUsuario($DatosPost);
			endif;
		}

		/**
		 * Index::AutenticacionConsultaPermisos()
		 *
		 * Genera la consulta de los permisos correspondientes
		 * @return ok
		 * @param bool $Consulta
		 * @return void
		 */
		private function AutenticacionConsultaPermisos($Consulta = false) {
			$ConsultaPermisos = $this->Modelo->ConsultarPermisos($Consulta[0]['IdPerfil']);
			if($ConsultaPermisos['Cantidad'] == 1):
				AppSession::Registrar($Consulta[0], $ConsultaPermisos[0]);
				header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Central'));
				exit();
			else:
				header("Location: ".NeuralRutasApp::RutaUrlApp('LogOut'));
				exit();
			endif;
		}

		/**
		 * Index::AutenticacionNoUsuario()
		 *
		 * Genera la validacion de la autenticacion correspondiente
		 * del usuario
		 * @return ok
		 * @return void
		 */
		private function AutenticacionNoUsuario($DatosPost = false) {
			$Supension = $this->Modelo->ConsultarSupension($DatosPost['Usuario'], hash('sha256', $DatosPost['Password']));
			if($Supension['Cantidad'] == 1):
				// -- Generar Vista Usuario Supendido
					$this->AutenticacionErrorRedireccion('Error', 'SinAutorizacion', 'Supendido');
			else:
				// -- Generar Vista Usuario y/o contraseña Incorrecto
				$this->AutenticacionErrorRedireccion('Error', 'SinAutorizacion');
			endif;
		}

		/**
		 * Index::AutenticacionErrorRedireccion()
		 *
		 * Genera el error de redireccion
		 * @return ok
		 * @param bool $modulo
		 * @param bool $controlador
		 * @param bool $metodo
		 * @return void
		 */
		private function AutenticacionErrorRedireccion($modulo = false, $controlador = false, $metodo = false) {
			header("Location: ".NeuralRutasApp::RutaUrlAppModulo($modulo, $controlador, $metodo));
			exit();
		}

		##############inicio de RecuperacionPassword

		/**
		 * Index::RecuperacionPassword()
		 *
		 * Genera la plantilla de recuperacion
		 * @return ok
		 * @return void
		 */
		public function RecuperacionPassword() {
			$Validacion = new NeuralJQueryFormularioValidacion(true, true, false);
			$Validacion->Requerido('email');
			$Validacion->Email('email');
			$Plantilla = new NeuralPlantillasTwig(APP);
			$Plantilla->Parametro('Scripts', $Validacion->Constructor('frm_Recuperacion'));
			$Plantilla->Parametro('Key', AppConversores::ASCII_HEX(NeuralCriptografia::Codificar(date("Y-m-d"), APP)));
			echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('Activacion', 'ActivacionCuenta.html')));
			unset($Validacion, $Plantilla);
			exit();
		}

		/**
		 * Index::EmailRecuperacionPassword()
		 *
		 * Genera la validacion de la peticion ajax
		 * @return ok
		 * @return void
		 */
		public function EmailRecuperacionPassword() {
			if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) == true AND mb_strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' AND $_SERVER['HTTP_REFERER'] != $_SERVER['HTTP_HOST']):
				$this->ERP_DatosPost();
			else:
				exit('La peticion ajax no puede ser procesada');
			endif;
		}

		/**
		 * Index::ERP_DatosPost()
		 *
		 * Valida si hay datos para procesar
		 * @return ok
		 * @return void
		 */
		private function ERP_DatosPost() {
			if(isset($_POST) == true AND isset($_POST['Key']) == true AND NeuralCriptografia::DeCodificar(AppConversores::HEX_ASCII($_POST['Key']), APP) == date("Y-m-d")):
				$this->ERP_DatosVacios();
			else:
				exit('No hay datos para ser procesados');
			endif;
		}

		/**
		 * Index::ERP_DatosVacios()
		 *
		 * Genera la validacion de los datos vacios en post
		 * @return ok
		 * @return void
		 */
		private function ERP_DatosVacios() {
			if(AppPost::DatosVacios($_POST) == false):
				$this->ERP_CorreoRegistrado();
			else:
				exit('El formulario indicado tiene datos vacios');
			endif;
		}

		/**
		 * Index::ERP_CorreoRegistrado()
		 *
		 * Genera la validacion del correo registrado
		 * @return ok
		 * @return void
		 */
		private function ERP_CorreoRegistrado() {
			$DatosPost = AppPost::FormatoMin(AppPost::FormatoEspacio(AppPost::LimpiarInyeccionSQL($_POST)));
			$Consulta = $this->Modelo->ConsultarCorreoUsuario($DatosPost['email']);

			if($Consulta['Cantidad'] > 0):
				$this->ERP_ActivacionPrevia($Consulta, $DatosPost);
			else:
				exit('El correo que ingreso no se encuentra asociado a ninguna cuenta registrada.');
			endif;
		}

		/**
		 * Index::ERP_ActivacionPrevia()
		 *
		 * Genera la validacion de la activacion previa
		 * @return ok
		 * @param bool $Consulta
		 * @param bool $DatosPost
		 * @return void
		 */
		private function ERP_ActivacionPrevia($Consulta = false, $DatosPost = false) {
			$Arreglo = array('IdUsuario' => $Consulta[0]['IdUsuario'], 'Correo' => $DatosPost['email'], 'NewPassword' => AppCorreos::GeneradorPassword(), 'Fecha_Validacion' => date("Y-m-d"));
			$ConsultaActivacionPrevia = $this->Modelo->BuscaActivacionPrevia($Arreglo);

			if($ConsultaActivacionPrevia['Cantidad'] > 0):
				exit('El correo que ingreso, ya cuenta con un proceso de renovación.');
			else:
				AppCorreos::EnviaActivacion(AppCorreos::CadenaHash($Consulta[0]['IdUsuario']), $DatosPost['email']);
				$this->Modelo->GuadarActivacion($Arreglo);
				echo "El correo fue enviado, espere un momento en lo que recibe la información.";
				exit();
			endif;
		}


		################ inicio de captcha

		/**
		 * Index::Captcha()
		 *
		 * genera imagen de captcha
		 * @return ok
		 * @return void
		 */
		public function Captcha() {
			AppCaptcha::Imagen();
		}

		/**
		 * Index::Activacion()
		 *
		 * Genera el proceso de activacion
		 * @return ok
		 * @param bool $Cuenta
		 * @return void
		 */
		public function Activacion($Cuenta = false) {
			$Activacion = NeuralCriptografia::DeCodificar(AppConversores::HEX_ASCII($Cuenta), APP);
			$Consulta = $this->Modelo->BuscarCuentaActivacion($Activacion);

			$Plantilla = new NeuralPlantillasTwig(APP);

			if($Consulta['Cantidad'] > 0):
				$Validacion = new NeuralJQueryFormularioValidacion(true, true, false);
				$Validacion->Requerido('Codigo');

				$Plantilla->Parametro('Cuenta', $Cuenta);
				$Plantilla->Parametro('Scripts', $Validacion->Constructor('frm_Recuperacion'));
				$Plantilla->Parametro('Key', AppConversores::ASCII_HEX(NeuralCriptografia::Codificar(date("Y-m-d"), APP)));
				echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('Login', 'ActivarPassword.html')));
			else:
				echo $Plantilla->MostrarPlantilla(AppPlantilla::Separador(array('Errores', 'CuentaActivada.html')));
			endif;
		}

		/**
		 * Index::ValidacionCuenta()
		 *
		 * Genera el proceso de la activacion de la cuenta
		 * validacion de peticion ajax
		 * @return void
		 */
		public function ValidacionCuenta() {
			if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) == true AND mb_strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' AND $_SERVER['HTTP_REFERER'] != $_SERVER['HTTP_HOST']):
				$this->VC_DatosPost();
			else:
				exit('La peticion ajax no puede ser procesada');
			endif;
		}

		/**
		 * Index::VC_DatosPost()
		 *
		 * Genera la validacion de los datos post
		 * @return ok
		 * @return void
		 */
		private function VC_DatosPost() {
			if(isset($_POST) == true AND isset($_POST['Key']) == true AND NeuralCriptografia::DeCodificar(AppConversores::HEX_ASCII($_POST['Key']), APP) == date("Y-m-d")):
				$this->VC_DatosVacios();
			else:
				exit('No hay datos para procesar');
			endif;
		}

		/**
		 * Index::VC_DatosVacios()
		 *
		 * Valida los datos vacios
		 * @return ok
		 * @return void
		 */
		private function VC_DatosVacios() {
			if(AppPost::DatosVacios($_POST) == false):
				$this->VC_Captcha();
			else:
				exit('Hay datos vacios en el formulario');
			endif;
		}

		/**
		 * Index::VC_Captcha()
		 *
		 * genera la validacion del captcha
		 * @return ok
		 * @return void
		 */
		private function VC_Captcha() {
			if(AppCaptcha::ValidarCaptcha($_POST['Codigo']) == true):
				$this->VC_ProcesoActivacion();
			else:
				exit('Captcha no valida');
			endif;
		}

		/**
		 * Index::VC_ProcesoActivacion()
		 *
		 * Genera el proceso de activacion
		 * @return ok
		 * @return void
		 */
		private function VC_ProcesoActivacion() {
			unset($_POST['Codigo']);
			$Cuenta = NeuralCriptografia::DeCodificar(AppConversores::HEX_ASCII($_POST['Activacion']), APP);
			$Consulta = $this->Modelo->BuscarCuentaActivacion($Cuenta);

			if($Consulta['Cantidad'] > 0):
				$this->VC_ProcesoActivacionGestion($Cuenta, $Consulta);
			else:
				exit('No hay proceso de activación');
			endif;
		}

		/**
		 * Index::VC_ProcesoActivacionGestion()
		 *
		 * genera la gestion de activacion
		 * @return ok
		 * @param bool $Cuenta
		 * @param bool $Consulta
		 * @return void
		 */
		private function VC_ProcesoActivacionGestion($Cuenta = false, $Consulta = false) {
			AppCorreos::EnviaNuevoPassword($Consulta[0]['NewPassword'], $Consulta[0]['Usuario'], $Consulta[0]['Correo']);
			$Matriz = array('Password' =>  hash('sha256', $Consulta[0]['NewPassword']));
			$Omitidos = array('IdUsuario', 'IdPerfil', 'Usuario', 'Status');
			$Condicion = array('IdUsuario' => $Consulta[0]['IdUsuario']);

			$this->Modelo->ActivaCuenta($Matriz, $Condicion, $Omitidos);

			$Matriz = array('Fecha_Validacion' => date("Y-m-d"), 'Status' => 'INACTIVO');
			$Condicion =array('IdActivacion' => $Consulta[0]['IdActivacion']);
			$Omitidos = array('IdActivacion', 'IdUsuario', 'Correo', 'NewPassword');

			$this->Modelo->ActualizaActivacion($Matriz, $Condicion, $Omitidos);
			echo "El proceso de activación fue a completado, en breve recibirá un correo con su nueva contraseña.";
			exit();
		}
	}