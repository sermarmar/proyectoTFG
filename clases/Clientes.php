<?php
class Cliente{
	protected $usuario;
	protected $password;
	protected $nombreCliente;
	protected $apellidos;
	protected $email;
	protected $fechaNacimiento;
	protected $direccion;
	protected $numero;
	protected $piso;
	protected $localidad;
	protected $provincia;
	protected $cp;
	
	public function __construct($parametros){
		$this->usuario = $parametros["Usuario"];
		$this->password = $parametros["Password"];
		$this->nombreCliente = $parametros["Nombre"];
		$this->apellidos=$parametros["Apellidos"];
		$this->email = $parametros["Email"];
		$this->fechaNacimiento=$parametros["FechaNacimiento"];
		$this->direccion=$parametros["Direccion"];
		$this->numero=$parametros["Numero"];
		$this->piso=$parametros["Piso"];
		$this->localidad=$parametros["Localidad"];
		$this->provincia=$parametros["Provincia"];
		$this->cp=$parametros["CodPostal"];
	}
	
	//Tener las variables
	public function getUsuario(){
		return $this->usuario;
	}
	public function getPass(){
		return $this->password;
	}
	public function getNombreCliente(){
		return $this->nombreCliente;
	}
	public function getApellidos(){
		return $this->apellidos;
	}
	public function getEmail(){
		return $this->email;
	}
	public function getDireccion(){
		return $this->direccion;
	}
	public function getNumero(){
		return $this->numero;
	}
	public function getPiso(){
		return $this->piso;
	}
	public function getLocalidad(){
		return $this->localidad;
	}
	public function getProvincia(){
		return $this->provincia;
	}
	public function getCP(){
		return $this->cp;
	}
	
	//Insertar nuevos restaurantes
	public static function registrarCliente($usuario,$password,$nombre,$apellidos,$email,$fechaNacimiento,$direccion,$num,$piso,$localidad,$telefono,$cp){
		$passHash=password_hash($password,PASSWORD_DEFAULT);
		
		$sql="INSERT cliente (Usuario,Password,Nombre,Apellidos,Email,Telefono,FechaNacimiento) VALUES('$usuario','$passHash','$nombre','$apellidos','$email','$telefono','$fechaNacimiento');";
		if(BD::consulta($sql)){
			$resultado=true;
		}
		else{
			$resultado=false;
		}
		$sql="INSERT INTO direccion (strDireccion,intNumero,strPiso,strLocalidad,intCP,strPersona)
		VALUES('$direccion',$num,'$piso','$localidad',$cp,'$usuario')";
		if(BD::consulta($sql)){
			$resultado=true;
		}
		else{
			$resultado=false;
		}
		return $resultado;
	}
}
?>