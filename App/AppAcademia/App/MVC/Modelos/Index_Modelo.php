<?php

	class Index_Modelo extends AppSQLConsultas {

		/**
		 * Metodo: Constructor
		 */
		function __Construct() {
			parent::__Construct();
		}

		// --- AUTENTIFICACION DE USUARIOS ---

		/**
		 * Metodo Publico
		 * ConsultarUsuario($Usuario = false, $Password = false)
		 *
		 * Consulta los datos del usuario
		 * retorna un array asociativo con los datos correspondientes
		 * @param $Usuario: username
		 * @param $Password: contraseña
		 *
		 **/
		public function ConsultarUsuario($Usuario = false, $Password = false) {
			if($Usuario == true AND $Password == true) {
				$Consulta = new NeuralBDConsultas(APP);
				$Consulta->Tabla('tbl_sistema_usuarios');
				$Consulta->Columnas(array_merge(self::ListarColumnas('tbl_informacion_usuarios', false, false, APP), self::ListarColumnas('tbl_sistema_usuarios', array('Password', 'Status'), false, APP)));
				$Consulta->InnerJoin('tbl_informacion_usuarios', 'tbl_sistema_usuarios.IdUsuario', 'tbl_informacion_usuarios.IdUsuario');
				$Consulta->Condicion("tbl_sistema_usuarios.Usuario = '$Usuario'");
				$Consulta->Condicion("tbl_sistema_usuarios.Password = '$Password'");
				$Consulta->Condicion("tbl_sistema_usuarios.Status = 'ACTIVO'");
				return $Consulta->Ejecutar(true, true);
			}
		}

		/**
		 * Metodo Publico
		 * ConsultarSupension($Usuario = false, $Password = false)
		 *
		 * Consulta si el usuario esta supendido
		 * @param $Usuario: Username
		 * @param $Password: Contraseña
		 *
		 * */
		public function ConsultarSupension($Usuario = false, $Password = false) {
			if($Usuario == true AND $Password == true) {
				$Consulta = new NeuralBDConsultas(APP);
				$Consulta->Tabla('tbl_sistema_usuarios');
				$Consulta->Columnas(array_merge(self::ListarColumnas('tbl_informacion_usuarios', array('IdUsuario', 'IdRegimen', 'Telefono', 'Movil', 'Pais', 'Estado', 'Municipio', 'Colonia', 'Localidad', 'Calle', 'Numero_Ext', 'Numero_Int', 'Codigo_Postal'), false, APP), self::ListarColumnas('tbl_sistema_usuarios', array('Password', 'Status'), false, APP)));
				$Consulta->InnerJoin('tbl_informacion_usuarios', 'tbl_sistema_usuarios.IdUsuario', 'tbl_informacion_usuarios.IdUsuario');
				$Consulta->Condicion("tbl_sistema_usuarios.Usuario = '$Usuario'");
				$Consulta->Condicion("tbl_sistema_usuarios.Password = '$Password'");
				$Consulta->Condicion("tbl_sistema_usuarios.Status != 'ACTIVO'");
				$Consulta->Condicion("tbl_sistema_usuarios.Status != 'ELIMINADO'");
				return $Consulta->Ejecutar(true, true);
			}
		}

		/**
		 * Metodo Publico
		 * ConsultarPermisos($Permisos = false)
		 *
		 * Genera la consulta de los datos correspondientes
		 * @param $Permiso: Identificador del permiso
		 *
		 */
		public function ConsultarPermisos($Permisos = false) {
			if($Permisos == true AND is_numeric($Permisos) == true) {
				$Consulta = new NeuralBDConsultas(APP);
				$Consulta->Tabla('tbl_sistema_usuarios_perfil');
				$Consulta->Columnas(self::ListarColumnas('tbl_sistema_usuarios_perfil', array('IdPerfil', 'Status'), false, APP));
				$Consulta->Condicion("IdPerfil = '$Permisos'");
				return $Consulta->Ejecutar(true, true);
			}
		}

		// --- ACTIVACION DE CUENTA ---

		/**
		 * Metodo Publico
		 * ConsultarCorreoUsuario($Correo = false)
		 *
		 * Consulta si existe cuenta con ese correo
		 * @param $Correo: Correo del usuario
		 *
		 * */
		public function ConsultarCorreoUsuario($Correo = false){
			if($Correo == true){
				$Consulta = new NeuralBDConsultas(APP);
				$Consulta->Tabla('tbl_sistema_usuarios');
				$Consulta->Columnas(self::ListarColumnas('tbl_sistema_usuarios', array('Password'), false, APP));
				$Consulta->InnerJoin('tbl_informacion_usuarios', 'tbl_sistema_usuarios.IdUsuario', 'tbl_informacion_usuarios.IdUsuario');
				$Consulta->Condicion("tbl_informacion_usuarios.Correo = '$Correo'");
				return $Consulta->Ejecutar(true, true);
			}
		}

		/**
		 * Metodo Publico
		 * BuscaActivacionPrevia($Datos = false)
		 *
		 * Busca si existe una activacion anterior en proceso
		 * @param $Datos: Arreglo de datos
		 *
		 * */
		public function BuscaActivacionPrevia($Datos = false){
			$Consulta = new NeuralBDConsultas(APP);
			$Consulta->Tabla('tbl_activacion_cuentas');
			$Consulta->Columnas('tbl_activacion_cuentas.Status');
			$Consulta->Condicion("tbl_activacion_cuentas.IdUsuario = '".$Datos['IdUsuario']."'");
			$Consulta->Condicion("tbl_activacion_cuentas.Status = 'PROCESANDO'");
			return $Consulta->Ejecutar(true, true);
		}

		/**
		 * Metodo Publico
		 * GuadarActivacion($Datos = false)
		 *
		 * Guarda los datos del proceso de activacion
		 * @param $Datos: Arreglo de datos
		 *
		 * */
		public function GuadarActivacion($Datos = false){
			$SQL = new NeuralBDGab(APP, 'tbl_activacion_cuentas');
			$SQL->Sentencia('IdUsuario', $Datos['IdUsuario']);
			$SQL->Sentencia('Correo', $Datos['Correo']);
			$SQL->Sentencia('NewPassword', $Datos['NewPassword']);
			$SQL->Sentencia('Fecha_Validacion', $Datos['Fecha_Validacion']);
			$SQL->Insertar();
		}

		/**
		 * Metodo Publico
		 * BuscarCuentaActivacion($Cuenta = false)
		 *
		 * Busca si existe un proceso de activacion
		 * @param $Cuenta: Cuenta a checar
		 *
		 * */
		public function BuscarCuentaActivacion($Cuenta = false) {
			$Consulta = new NeuralBDConsultas(APP);
			$Consulta->Tabla('tbl_activacion_cuentas');
			$Consulta->Columnas(array_merge(self::ListarColumnas('tbl_activacion_cuentas', false, false, APP), self::ListarColumnas('tbl_sistema_usuarios', array('Status'), false, APP)));
			$Consulta->InnerJoin('tbl_sistema_usuarios', 'tbl_activacion_cuentas.IdUsuario', 'tbl_sistema_usuarios.IdUsuario');
			$Consulta->Condicion("tbl_activacion_cuentas.IdUsuario = '$Cuenta'");
			$Consulta->Condicion("tbl_activacion_cuentas.Status = 'PROCESANDO'");
			return $Consulta->Ejecutar(true, true);
		}

		// --- Registro de _Cuentas ---

		public function BuscarCuentaRegistrada($Correo = false) {
			$Consulta = new NeuralBDConsultas(APP);
			$Consulta->Tabla('tbl_sistema_usuarios');
			$Consulta->Columnas(array_merge(self::ListarColumnas('tbl_sistema_usuarios', array('Status'), false, APP)));
			$Consulta->Condicion("tbl_sistema_usuarios.Usuario = '$Correo'");
			return $Consulta->Ejecutar(true, true);
		}

		public function GuardarNuevaCuenta($Arreglo = false) {
			if($Arreglo == true AND is_array($Arreglo)){
				return self::GuardarDatos($Arreglo, 'tbl_sistema_usuarios', array('IdUsuario', 'Status'), APP);
			}
		}

		public function BuscaNuevaCuenta($Correo = false) {
			$Consulta = new NeuralBDConsultas(APP);
			$Consulta->Tabla('tbl_sistema_usuarios');
			$Consulta->Columnas(array_merge(self::ListarColumnas('tbl_sistema_usuarios', array('Status'), false, APP)));
			$Consulta->Condicion("tbl_sistema_usuarios.Usuario = '$Correo'");
			return $Consulta->Ejecutar(false, true);
		}

		public function GuardarNuevaInformacionCuenta($Arreglo = false) {
			if($Arreglo == true AND is_array($Arreglo)){
				return self::GuardarDatos($Arreglo, 'tbl_sistema_informacion_usuario', array('IdInformacion', 'Cargo', 'Telefono'), APP);
			}
		}

		public function GuardarNuevaEmpresaCuenta($Arreglo = false, $Omitidos = false) {
			if($Arreglo == true AND is_array($Arreglo)){
				return self::GuardarDatos($Arreglo, 'tbl_informacion_usuarios', $Omitidos, APP);
			}
		}

		// --- Activacion de Cuentas ---

		public function BuscarCuenta($Correo = false) {
			$Consulta = new NeuralBDConsultas(APP);
			$Consulta->Tabla('tbl_sistema_usuarios');
			$Consulta->Columnas(array_merge(self::ListarColumnas('tbl_sistema_usuarios', array('Status'), false, APP)));
			$Consulta->Condicion("tbl_sistema_usuarios.Usuario = '$Correo'");
			$Consulta->Condicion("tbl_sistema_usuarios.Status = 'INACTIVO'");
			return $Consulta->Ejecutar(true, true);
		}

		public function ActivaCuenta($Arreglo = false, $Condicion = false, $Omitidos = false) {
			if(is_array($Arreglo) == true AND is_array($Condicion) == true AND is_array($Omitidos) == true ) {
				return self::ActualizarDatos($Arreglo, $Condicion, 'tbl_sistema_usuarios', $Omitidos, APP);
			}
		}

		public function ActualizaActivacion($Arreglo = false, $Condicion = false, $Omitidos = false){
			if(is_array($Arreglo) == true AND is_array($Condicion) == true AND is_array($Omitidos) == true ) {
				return self::ActualizarDatos($Arreglo, $Condicion, 'tbl_activacion_cuentas', $Omitidos, APP);
			}
		}

	}