<?php

	class AppFechas {

		private static $TimeZone = 'America/Monterrey';

		/**
		 * Metodo publico estatico
		 * ObtenerFechaActual()
		 *
		 * Obtiene la fecha actual.
		 * @return string: Fecha en formato, (Y-m-d) o (AAAA-MM-DD)
		 */
		public static function ObtenerFechaActual(){
			$NowDateTime = new \DateTime('now');
			$NowDateTime->setTimezone(new \DateTimeZone(self::$TimeZone));
			return $NowDateTime->format("Y-m-d");
		}

		/**
		 * Metodo publico estatico
		 * ObtenerHoraActual.
		 *
		 * Obtiene la hora actual.
		 * @return string: Hora en formato, (H:i:s) o (HH:MM:SS).
		 */
		public static function ObtenerHoraActual(){
			$NowDateTime = new \DateTime('now');
			$NowDateTime->setTimezone(new \DateTimeZone(self::$TimeZone));
			return $NowDateTime->format("H:i:s");
		}

		/**
		 * Metodo Publico
		 * ObtenerNombreDiaActual()
		 *
		 * Devuelve el nombre del dia Actual.
		 * @return mixed
		 */
		public static function ObtenerNombreDiaActual(){
			$NowDateTime = new \DateTime('now');
			$NowDateTime->setTimezone(new \DateTimeZone(self::$TimeZone));
			$Dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
			return $Dias[$NowDateTime->format("w")];
		}

		/**
		 * Metodo publico estatico
		 * ObtenerDatetimeActual()
		 *
		 * Obtiene el la fecha y hora actual en formato datetime.
		 * @return string: Hora y Fecha, Y-m-d H:i:s.
		 */
		public static function ObtenerDatetimeActual(){
			$NowDateTime = new \DateTime('now');
			$NowDateTime->setTimezone(new \DateTimeZone(self::$TimeZone));
			return $NowDateTime->format("Y-m-d H:i:s");
		}

		/**
		 * Metodo publico estatico
		 * DatetimeInicioDiaActual()
		 *
		 * Devuelve la fecha actual con la hora en ceros.
		 * @return string: Fecha inicio dia actual, Y-m-d 00:00:00.
		 */
		public static function DatetimeInicioDiaActual(){
				$NowDateTime = new \DateTime('now');
				$NowDateTime->setTimezone(new \DateTimeZone(self::$TimeZone));
				return $NowDateTime->format("Y-m-d 00:00:00");
		}

		/**
		 * Metodo publico estatico
		 * DatetimeInicioMesActual()
		 *
		 * Desvuelve la fecha de primer dia y la primera hora del mes y a�o actual.
		 * @return string: "Y-m-01 00:00:00"
		 */
		public static function DatetimeInicioMesActual(){
			$NowDateTime = new \DateTime('now');
			$NowDateTime->setTimezone(new \DateTimeZone(self::$TimeZone));
			return $NowDateTime->format("Y-m-01 00:00:00");
		}

		/**
		 * Metodo publico estatico
		 *DatetimeInicioAnioActual
		 *
		 * Obtiene el primer mes, hora y dia del a�o actual.
		 * @return string: "Y-01-01 00:00:00"
		 */
		public static function DatetimeInicioAnioActual(){
			$NowDateTime = new \DateTime('now');
			$NowDateTime->setTimezone(new \DateTimeZone(self::$TimeZone));
			return $NowDateTime->format("Y-01-01 00:00:00");
		}

		/**
		 * Metodo publico
		 * ObtenerDatetime($Date = false, $Time = false)
		 *
		 *Transforma el tipo date y time a un datetime.
		 * @param bool|false $Date
		 * @param bool|false $Time
		 * @return bool|string: Devuelve un Datetime.
		 */
		public static function ObtenerDatetime($Date = false, $Time = false){
			if($Date == true AND $Time == true){
				return date('Y-m-d H:i:s', strtotime("$Date $Time"));
			}

		}

		/**
		 * Metodo Publico
		 * ConvertirDatetimeInicioDia($Date = false)
		 *
		 * Devuelve la fecha en formato datetime, con la hora al inicio del dia.
		 * @param bool|false $Date: 'MM/DD/YYYY' o 'YYYY-MM-DD'
		 * @return bool|string: Y-m-d 00:00:00
		 */
		public static function ConvertirDatetimeInicioDia($Date = false){
			if($Date == true){
				return date('Y-m-d 00:00:00', strtotime($Date));
			}
		}

		/**
		 * Metodo Publico
		 * ConvertirDatetimeFinDia($Date = false)
		 *
		 * Devuelve la fecha en formato datetime, con la hora al fin del dia.
		 * @param bool|false $Date: 'MM/DD/YYYY' o 'YYYY-MM-DD'
		 * @return bool|string: Y-m-d 23:59:59
		 */
		public static function ConvertirDatetimeFinDia($Date = false){
			if($Date == true){
				return date('Y-m-d 23:59:59', strtotime($Date));
			}
		}

		/**
		 * Metodo publico estatico
		 * ObtenerFecha($Datetime = false)
		 *
		 * Devuelve solo la fecha de un Datetime, en formato
		 * @param $Datetime
		 * @return mixed
		 */
		public static function ObtenerFecha($Datetime = false){
			$Dates = explode(' ', $Datetime);
			return $Dates[0];
		}

		/**
		 * Metodo publico estatico
		 * ObtenerHora($Datetime = false)
		 *
		 * Obtiene solo la hora del objeto $Datetime recibido.
		 * @param bool|false $Datetime
		 * @return mixed: Segunda segunda parte despues del espacio.
		 */
		public static function ObtenerHora($Datetime = false){
			$Dates = explode(' ', $Datetime);
			return $Dates[1];
		}

		/**
		 * Metodo publico estatico
		 * ObtenerNombreMes($NumeroMes = false)
		 *
		 * Develve el nombre del mes correspondiente a su numero.
		 * @param bool|false $NumeroMes: (1 = Enero, 2 = Febrero, ...)
		 * @return bool:false|Mes.
		 */
		public static function ObtenerNombreMes($NumeroMes = false){
			if(is_numeric($NumeroMes) AND $NumeroMes >= 0 AND $NumeroMes < 12){
				$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
				return $meses[$NumeroMes - 1];
			}else{
				return false;
			}
		}

		/**
		 * Metodo publico estatico
		 * ObtenerMeses()
		 *
		 * Devuelve los meses del a�o.
		 * @return array: Meses del a�o
		 */
		public static function ObtenerMeses(){
			return array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		}

		/**
		 * Metodo Publico
		 * AppUtilidades::FormatoFecha($Arreglo = false)
		 *
		 * da formato a una fecha d/m/Y a d-m-Y
		 * @param $Fecha: fechas tipo d/m/Y
		 * @return string fechas Y-m-d
		 * */
		public static function FormatoFecha($Fecha = false){
			if($Fecha == true){
				$Fecha = str_replace("/", "-", $Fecha);
				$NowDateTime = new \DateTime($Fecha);
				return $NowDateTime->format("Y-m-d");				 
			}
		}

		/**
		 * Metodo Publico
		 * FormatoFechaDDMMYYYY($Fecha = false)
		 *
		 * @param bool|false $Fecha: Y-m-d
		 * @return string: d/m/Y
		 */
		public static function FormatoFechaDDMMYYYY($Fecha = false){
			if($Fecha == true){
				$Fecha = str_replace("-", "/", $Fecha);
				$NowDateTime = new \DateTime($Fecha);
				return $NowDateTime->format("d/m/Y");
			}
		}

		/**
		 * Metodo publico
		 * FormatoFechaYYYYMMDD($Fecha = false)
		 *
		 * Convierte la fecha recibida a formatao YYYY-MM-DD, agregarndo el prefico 20YY-MM-DD.
		 * @param bool|false $Fecha: 151018
		 * @return string: 2015-10-18
		 */
		public static function FormatoFechaYYYYMMDD($Fecha = false){
			if($Fecha == true){
				$Caracteres = str_split($Fecha, 2);
				$Caracteres[0] = '20'.$Caracteres[0];
				$FechaLimite =  implode('/', $Caracteres);
				return AppFechas::FormatoFecha($FechaLimite);
			}
		}

		/**
		 * Metodo publico estatico
		 * ConvertirSegundosTime($Segundos = false)
		 *
		 * Convierte los segundos recibidos a formato minutos.
		 * @param bool|false $Segundos
		 * @return string: 'H:i:s'
		 */
		public static function ConvertirSegundosTime($Segundos = false){
			if($Segundos == true){
				return gmdate("H:i:s", $Segundos);
			}
		}

		/**
		 * Metodo publico estatico
		 * ConvertirSegundosMinutos($Segundos = false, $Redondeo = false)
		 *
		 * Convierte segundos de acuerdo a los parametros recibidos.
		 * @param bool|false $Segundos: Segundos.
		 * @param bool|false $Redondeo: La forma del redondeo UP, DOWN, ''
		 * @return float
		 */
		public static function ConvertirSegundosMinutos($Segundos = false, $Redondeo = false){
			if($Segundos == true){
				if($Redondeo == true){
					switch($Redondeo){
						case 'UP':
							return round(floor($Segundos / 60), 0, PHP_ROUND_HALF_UP);
							break;
						case 'DOWN':
							return round(floor($Segundos / 60), 0, PHP_ROUND_HALF_DOWN);
							break;
						default:
							return round(floor($Segundos / 60), 0);
							break;
					}
				}else{
					return floor($Segundos / 60);
				}
			}
		}

		/**
		 * Metodo Publico
		 * ConvertirMinutosSegundos($Minutos = false)
		 *
		 * Devuelve el equivalente a segundos.
		 * @param bool|false $Minutos
		 * @return float
		 */
		public static function ConvertirMinutosSegundos($Minutos = false){
			if($Minutos == true){
				return round(60*$Minutos);
			}
		}


	}


