<?php
	
	class AppSQLConsultas extends Modelo {
		
		/**
		 * Metodo Contructor
		 * 
		 */
		function __Construct() {
			parent::__Construct();
		}
		
		/**
		 * Metodo Privado
		 * ListarColumnas($Tabla = false, $Omitidos = false)
		 * 
		 * @param $Tabla: Nombre de la Tabla a Listar las Columnas
		 * @param $Omitidos: array incremental con el nombre de las columnas a omitir
		 * @param $Alias: es un array asociativo
		 * @example array('Columna' => 'Alias')
		 * @param $Apliacion: Arreglo incrementa de la aplicacion si no se especifica se toma el del modulo
		 * @example array('APLICACION')
         * 
		 */
		public static function ListarColumnas($Tabla = false, $Omitidos = false, $Alias = false, $Aplicacion = 'DEFAULT') {
			if($Tabla == true) {
				$Conexion = NeuralConexionDB::DoctrineDBAL($Aplicacion);
				$Consulta = $Conexion->prepare("DESCRIBE $Tabla");
				$Consulta->execute();
				$Lista = $Consulta->fetchAll(PDO::FETCH_ASSOC);
				$Cantidad = $Consulta->rowCount();
				$Matriz = (is_array($Omitidos) == true) ? array_flip($Omitidos) : array();
				$AliasBase = (is_array($Alias) == true) ? $Alias : array();
				for ($i=0; $i<$Cantidad; $i++) {
					if(array_key_exists($Lista[$i]['Field'], $Matriz) == false) {
						if(array_key_exists($Lista[$i]['Field'], $AliasBase) == true) {
							$Columna[] = $Tabla.'.'.$Lista[$i]['Field'].' AS '.$AliasBase[$Lista[$i]['Field']];
						}
						else {
							$Columna[] = $Tabla.'.'.$Lista[$i]['Field'];
						}
					}
				}
				//return implode(', ', $Columna);
				return $Columna;
			}
		}
		
		/**
		 * Metodo Publico
		 * GuardarDatos($Array = false, $Tabla = false, $Omitidos = false, $Aplicacion = 'DEFUALT');
		 * 
		 * Guarda Datos en la tabla seleccionada
		 * @param $Array: array asociativo con los datos a guardar
		 * @param $Tabla: donde se guardaran los datos
		 * @param $Omitidos: array incremental con las columnas omitidas donde no se validara
		 * @example array('id', 'nombre', 'apellidos')
		 * @param $Aplicacion: Aplicacion que se utilizara para conexion a BD
		 * 
		 * */
		public static function GuardarDatos($Array = false, $Tabla = false, $Omitidos = false, $Aplicacion = 'DEFAULT') {
			if($Array == true AND is_array($Array) == true AND $Tabla == true AND is_array($Omitidos) == true AND $Aplicacion == true) {
				$Matriz = self::ListarColumnasTabla($Tabla, $Omitidos, 'ARRAY', $Aplicacion);
				if(count($Array) == count($Matriz)) {
					$SQL = new NeuralBDGab($Aplicacion, $Tabla);
					while(list($Columna, $Valor) = each($Array)) {
						if(array_key_exists($Columna, $Matriz) == true) {
							$SQL->Sentencia($Columna, $Valor);
						}
					}
					$SQL->Insertar();
					unset($Aplicacion, $Array, $Omitidos, $Tabla, $Columna, $Matriz, $SQL, $Valor);
					return false;
				}
				else {
					return 'Las Columnas No Coinciden Con la Cantidad de Datos Recibidos';
				}	
			}
		}
		
		/**
		 * Metodo Publico
		 * ActualizarDatos($Array = false, $Condiciones = false, $Tabla = false, $Omitidos = false, $Aplicacion = 'DEFULAT');
		 * 
		 * Guarda Datos en la tabla seleccionada
		 * @param $Array: array asociativo con los datos a actualizar
		 * @param $Condiciones: array asociativo con las condiciones a cumplir
		 * @example array('id' => '2', 'estado' => 'ACTIVO')
		 * @param $Tabla: donde se guardaran los datos
		 * @param $Omitidos: array incremental con las columnas omitidas donde no se validara
		 * @example array('id', 'nombre', 'apellidos')
		 * @param $Aplicacion: Aplicacion que se utilizara para conexion a BD
		 * 
		 * */
		public static function ActualizarDatos($Array = false, $Condiciones = false, $Tabla = false, $Omitidos = false, $Aplicacion = 'DEFULAT') {
			if($Array == true AND is_array($Array) == true AND is_array($Condiciones) == true AND $Tabla == true AND is_array($Omitidos) == true AND $Aplicacion == true) {
				$Matriz = self::ListarColumnasTabla($Tabla, $Omitidos, 'ARRAY', $Aplicacion);
				if(count($Array) == count($Matriz)) {
					$SQL = new NeuralBDGab($Aplicacion, $Tabla);
					foreach ($Array AS $Columna => $Valor) {
						if(array_key_exists($Columna, $Matriz) == true) {
							$SQL->Sentencia($Columna, $Valor);
						}
					}
					foreach ($Condiciones AS $CColumna => $CValor) {
						$SQL->Condicion($CColumna, $CValor);
					}
					$SQL->Actualizar();
					unset($Aplicacion, $Array, $Condiciones, $Omitidos, $Tabla, $CColumna, $CValor, $Matriz, $SQL, $Valor);
				}
				else {
					return 'Las Columnas No Coinciden Con la Cantidad de Datos Recibidos';
				}
			}
		}
		
		/**
		 * Metodo Publico
		 * EliminarDatos($Condiciones = false, $Tabla = false, $Aplicacion = 'DEFUALT');
		 * 
		 * Elimina los datos seleccionados
		 * @param $Condiciones: array asociativo con las condiciones correspondientes
		 * @example array('id' => '4', 'Estado' => 'ACTIVO')
		 * @param $Tabla: tabla donde se realizara el procedimiento
		 * @param $Aplicacion: aplicacion donde se tomaran los datos de BD
		 * 
		 * */
		public static function EliminarDatos($Condiciones = false, $Tabla = false, $Aplicacion = 'DEFUALT') {
			if(is_array($Condiciones) == true AND $Tabla == true AND $Aplicacion == true) {
				$SQL = new NeuralBDGab($Aplicacion, $Tabla);
				while(list($Columna, $Valor) = each($Condiciones)) $SQL->Condicion($Columna, $Valor);
				$SQL->Eliminar();
			}
		}
		
		/**
		 * Metodo Privado
		 * ListarComentariosColumTabla($Tabla = false)
		 * 
		 * Lista los comentarios de la tabla
		 * @param $Tabla: Nombre de la tabla
		 * 
		 * */
		private static function ListarComentariosColumTabla($Tabla = false) {
			if($Tabla == true) {
				$Conexion = NeuralConexionDB::DoctrineDBAL($Aplicacion);
				$Consulta = $Conexion->prepare("SELECT COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$Tabla'");
				$Datos = $Consulta->execute();
				$Cantidad = count($Datos);
				for ($i=0; $i<$Cantidad; $i++) {
					$Lista['0'][] = $Datos[$i]['COLUMN_COMMENT'];
				}
				unset($Tabla, $Cantidad, $Consulta, $Datos);
				return $Lista;
			}
		}
		
		/**
		 * Metodo Privado
		 * ListarColumnasTabla($Tabla = false, $Omitidos = array() ,$Tipo = 'LISTA')
		 * 
		 * Lista las columnas de una tabla segun la necesidad
		 * @param $Tabla: Nombre de la Tabla a Listar las Columnas
		 * @param $Omitidos: array incremental con el nombre de las columnas a omitir
		 * @param $Tipo: el tipo de datos que requerimos
		 * @return LISTA: Lista ordenada separa por comas
		 * @return ARRAY: devuelve un array asociativo con el nombre de las columnas
		 * 
		 * */
		private static function ListarColumnasTabla($Tabla = false, $Omitidos = array() ,$Tipo = 'LISTA', $Aplicacion = 'DEFAULT') {
			if($Tabla == true AND is_array($Omitidos) == true AND $Tipo == true AND $Aplicacion == true) {
				$Conexion = NeuralConexionDB::DoctrineDBAL($Aplicacion);
				$Consulta = $Conexion->prepare("DESCRIBE $Tabla");
				$Consulta->execute();
				$Datos = $Consulta->fetchAll();
				$Cantidad = count($Datos);
				$Matriz = array_flip($Omitidos);
				for ($i=0; $i<$Cantidad; $i++) {
					if(array_key_exists($Datos[$i]['Field'], $Matriz) == false) {
						$Lista[] = $Datos[$i]['Field'];
					}
				}
				unset($Tabla, $Omitidos, $Consulta, $Datos, $Cantidad, $Matriz, $Aplicacion);
				if($Tipo == 'LISTA') {
					return implode(', ', $Lista);
				}
				else {
					return array_flip($Lista);
				}
			}
		}
		
	}