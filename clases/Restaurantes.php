<?php
require_once("BD.php");

class Restaurante{
	protected $cif;
	protected $password;
	protected $nombreRestaurante;
	protected $email;
	protected $direccion;
	protected $numero;
	protected $localidad;
	protected $telefono;
	protected $cp;
	protected $categoria;
	protected $popularidad;
	protected $foto;
	protected $portada;
	protected $moneda;
	
	public function __construct($parametros){
		$this->cif = $parametros["CIF"]??"";
		$this->password = $parametros["Password"]??"";
		$this->nombreRestaurante = $parametros["Restaurante"]??"";
		$this->email = $parametros["Email"]??"";
		$this->direccion=$parametros["Direccion"]??"";
		$this->numero=$parametros["Numero"]??"";
		$this->localidad=$parametros["Localidad"]??"";
		$this->telefono=$parametros["Telefono"]??"";
		$this->cp=$parametros["CodPostal"]??"";
		$this->categoria=$parametros["Categoria"]??"";
		$this->popularidad=$parametros["Popularidad"]??"";
		$this->foto=$parametros["imagen"]??"";
	}
	
	//Tener las variables
	public function getCIF(){
		return $this->cif;
	}
	public function getPass(){
		return $this->password;
	}
	public function getRestaurante(){
		return $this->nombreRestaurante;
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
	public function getLocalidad(){
		return $this->localidad;
	}
	public function getTelefono(){
		return $this->telefono;
	}
	public function getCP(){
		return $this->cp;
	}
	public function getCategoria(){
		return $this->categoria;
	}public function getPopularidad(){
		return $this->popularidad;
	}
	public function getFoto(){
		return $this->foto;
	}
	public function getMoneda(){
		return $this->moneda;
	}
	
	//Insertar nuevos restaurantes
	public static function registrarRestaurante($CIF,$password,$nombre,$email,$telefono,$direccion,$num,$localidad,$cp,$categoria){
		$passHash=password_hash($password,PASSWORD_DEFAULT);
		
		$sql="INSERT restaurante (CIF,Password,Restaurante,Email,Telefono,Direccion,Numero,Localidad,CodPostal,Categoria) 
		VALUES ('$CIF','$passHash','$nombre','$email','$telefono','$direccion','$num','$localidad','$cp','$categoria');";
		if(BD::consulta($sql)){
			$resultado=true;
		}
		else{
			$resultado=false;
		}
		$sql="INSERT INTO cartera (idRestaurante) VALUES ('$CIF');";
		if(BD::consulta($sql)){
			$resultado=true;
		}
		else{
			$resultado=false;
		}
		return $resultado;
	}
	
	//Los datos de los restaurantes
	public static function obtenerRestaurantes($categoria,$estrellas){
		if($categoria=="todo" && $estrellas=="todo"){
			$sql = "SELECT * FROM restaurante
			INNER JOIN comidas ON comidas.codCif=restaurante.CIF
			GROUP BY CIF";
		}
		else if($categoria=="todo"){
			$sql = "SELECT * FROM restaurante
			INNER JOIN comidas ON comidas.codCif=restaurante.CIF
			WHERE Popularidad=$estrellas
			GROUP BY CIF";
		}
		else if($estrellas=="todo"){
			$sql = "SELECT * FROM restaurante
			INNER JOIN comidas ON comidas.codCif=restaurante.CIF
			WHERE restaurante.Categoria=$categoria
			GROUP BY CIF";
		}
		else{
			$sql="SELECT * FROM restaurante
			INNER JOIN comidas ON comidas.codCif=restaurante.CIF
			WHERE Popularidad=$estrellas AND restaurante.Categoria=$categoria
			GROUP BY CIF";
		}
		
		if ($resultado_consulta = BD::consulta($sql)){
			while ($registro = $resultado_consulta->fetch()){
				$restaurante = new Restaurante($registro);
				$resultado[] = $restaurante;
			}
		}
		return $resultado;
	}
	
	public static function buscarRestaurantes($valor){
		$sql = "SELECT * FROM restaurante
		INNER JOIN fotos ON fotos.strCIF=restaurante.CIF
		WHERE Restaurante='$valor'";
		
		if ($resultado_consulta = BD::consulta($sql)){
			while ($registro = $resultado_consulta->fetch()){
				$restaurante = new Restaurante($registro);
			}
		}
		return $restaurante;
	}
	
	public static function buscarRestaurante($valor){
		$sql = "SELECT * FROM restaurante
		INNER JOIN fotos ON fotos.strCIF=restaurante.CIF
		WHERE CIF='$valor'";
		
		if ($resultado_consulta = BD::consulta($sql)){
			while ($registro = $resultado_consulta->fetch()){
				$restaurante = new Restaurante($registro);
			}
		}
		return $restaurante;
	}
	
	//Cartera de un restaurante
	public static function cartera($cif){
		$cartera=BD::consulta("SELECT * FROM cartera WHERE idRestaurante='$cif'");
		$registro=$cartera->fetch();
		
		$resultado=$registro["intMoneda"];
		
		return $resultado;
	}
	
	public static function contarPopular($cod){
		$comida=0;
		$sql = "SELECT Popularidad FROM restaurante WHERE CIF='$cod'";
		if ($resultado_consulta = BD::consulta($sql)){
			while ($registro = $resultado_consulta->fetch()){
				$popular = $registro["Popularidad"];
			}
		}
		return $popular;
	}
}
?>