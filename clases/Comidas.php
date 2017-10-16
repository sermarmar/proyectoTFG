<?php
require_once("BD.php");
class Comida{
	protected $id;
	protected $nombreComida;
	protected $precio;
	protected $ingredientes;
	protected $categoria;
	protected $estado;
	protected $cif;
	
	public function __construct($parametros){
		$this->id=$parametros["intIDComida"];
		$this->nombreComida = $parametros["NombreComida"];
		$this->precio = $parametros["Precio"];
		$this->ingredientes = $parametros["Ingredientes"];
		$this->categoria=$parametros["Categoria"];
		$this->estado=$parametros["enumPublicado"]??"";
		$this->cif = $parametros["codCif"];
	}
	
	
	//Tener las variables
	public function getIDComida(){
		return $this->id;
	}
	public function getComida(){
		return $this->nombreComida;
	}
	public function getPrecio(){
		return $this->precio;
	}
	public function getIngredientes(){
		return $this->ingredientes;
	}
	public function getCategoria(){
		return $this->categoria;
	}
	public function getEstado(){
		return $this->estado;
	}
	public function getCIF(){
		return $this->cif;
	}
	
	//Insertar la comida
	public static function anadirComida($name,$precio,$ingredientes,$categoria,$cod){
		$resultado=false;
		$sql="INSERT comidas (NombreComida,Precio,Ingredientes,Categoria,codCif) 
		VALUES ('$name',$precio,'$ingredientes','$categoria','$cod');";
		if(BD::consulta($sql)){
			$resultado=true;
		}
		return $resultado;
	}
	
	//Contar comidas
	public static function contarComidas($cod){
		$comida=0;
		$sql = "SELECT count(*) AS num FROM comidas WHERE codCif='$cod'";
		if ($resultado_consulta = BD::consulta($sql)){
			while ($registro = $resultado_consulta->fetch()){
				$comida = $registro ["num"];
			}
		}
		return $comida;
	}
	
	//Mostrar las comidas perteneciendo al restaurante
	public static function obtenerComidas($cod){
		$resultado="";
		$sql = "SELECT * FROM comidas WHERE codCif='$cod'";
		if ($resultado_consulta = BD::consulta($sql)){
			while ($registro = $resultado_consulta->fetch()){
				$comidas = new Comida($registro);
				$resultado[] = $comidas;
			}
		}
		return $resultado;
	}
	
	public static function comidasPublicadas($cod){
		$resultado="";
		$sql = "SELECT * FROM comidas 
		WHERE enumPublicado='Publicar' AND codCif='$cod'";
		if ($resultado_consulta = BD::consulta($sql)){
			while ($registro = $resultado_consulta->fetch()){
				$comidas = new Comida($registro);
				$resultado[] = $comidas;
			}
		}
		return $resultado;
	}

}
?>