<?php
require_once 'Restaurantes.php';
require_once 'Clientes.php';
require_once 'Comidas.php';

//HOST: mysql.hostinger.es     	//  localhost
//dbname: id1735562_airfood		//  airfood
//dbuser: id1735562_airfood		//  restaurante
//dbpwd: comida				//  comida

class BD {
	
	//Conexion de BBDD
	public static function consulta($sql){
		$resultado = false;
		try{
			$dsn = "mysql:host=localhost;dbname=airfood;charset=utf8";   
			$dbuser = "restaurante";
			$dbpwd = "comida";
			$conn = new PDO($dsn,$dbuser,$dbpwd);
		}
		catch (Exception $e){
			return $resultado;
		}
		try {
			$resultado = $conn->query($sql);
		} catch (Exception $e) {
			return $e->getMessage();		
		}
		return $resultado;
	}
	
	//Comprobar la entrada de restaurante
	public static function combrobarCIF($user,$pwd){
		$usuario = $user;
		$sql = "SELECT Password FROM restaurante WHERE CIF='$user'";
		if ($resultado = self::consulta($sql)){
			$fila = $resultado->fetch();
			if(password_verify($pwd,$fila["Password"])){
				return true;
			}
			else{
				return false;
			}
		}
		else {
			return false;
		}
	}
	
	
	//Comprobar la entrada del cliete
	public static function comprobarCliente($user, $pwd){
		$usuario=$user;
		$sql="SELECT Password FROM cliente WHERE Usuario='$user'";
		if($resultado=self::consulta($sql)){
			$fila=$resultado->fetch();
			if(password_verify($pwd,$fila["Password"])){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return true;
		}
	}
	
}