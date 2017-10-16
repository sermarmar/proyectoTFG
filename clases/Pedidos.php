<?php
require_once("BD.php");
require_once("Comidas.php");

class Pedido{
	protected $idPedido;
	protected $idComida;
	protected $unidad;
	protected $cliente;
	protected $hora;
	protected $fecha;
	protected $direccion;
	protected $nombreComida;
	protected $precio;
	
	public function __construct($parametros){
		$this->idPedido = $parametros["intIDPedido"];
		$this->idComida = $parametros["codComida"];
		$this->unidad = $parametros["unidad"];
		$this->cliente=$parametros["strCliente"];
		$this->nombreComida = $parametros["NombreComida"]??"";
		$this->precio = $parametros["Precio"]??"";
	}
	
	//Tener las variables
	public function getPedido(){
		return $this->idPedido;
	}
	public function getIDComida(){
		return $this->idComida;
	}
	public function getUnidades(){
		return $this->unidad;
	}
	public function getBebidas(){
		return $this->bebidas;
	}
	public function getCliente(){
		return $this->cliente;
	}
	public function getHora(){
		return $this->hora;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getDireccion(){
		return $this->direccion;
	}
	public function getComida(){
		return $this->nombreComida;
	}
	public function getPrecio(){
		return $this->precio;
	}
	
	//Guardar pedidos
	public function guardarPedidos($idComida,$unidades,$cliente){
		$alerta="";
		$preciosTotales=0;
		$sql="";
		
		//Consultar para sacar el número de pedido para evitar el cambios
		$otraLista=BD::consulta("SELECT * FROM pedido
		INNER JOIN cliente ON cliente.Usuario=pedido.strCliente
		WHERE Estado='Guardado' AND cliente.Usuario='$cliente'");
		$insertSegunda=$otraLista->fetch();
		
		//Consultar el CIF del restaurante que saco el primer pedido
		$cifPedido=BD::consulta("SELECT DISTINCT codCif FROM pedido
		INNER JOIN cliente ON cliente.Usuario=pedido.strCliente
		INNER JOIN lista ON lista.intIDPedido=pedido.intIDPedido
		INNER JOIN comidas on comidas.intIDComida=lista.codComida
		WHERE Estado='Guardado' AND cliente.Usuario='$cliente'");
		$cif=$cifPedido->fetch();
		
		//Comprobar la comida que pertenece al restaurante
		$comidaCif=BD::consulta("SELECT codCif FROM comidas
		WHERE intIDComida=$idComida");
		$cifComida=$comidaCif->fetch();
		
		//Consultar la existencia del pedido de un cliente
		$existePedido=BD::consulta("SELECT count(*) AS cont FROM pedido
		INNER JOIN cliente ON cliente.Usuario=pedido.strCliente
		WHERE Estado='Guardado' AND cliente.Usuario='$cliente'");
		$existe=$existePedido->fetch();
		
		//Se inserta esta condición
		if($existe["cont"]==0){
			//Insertar el pedido por el cliente
			BD::consulta("INSERT INTO pedido (Estado,strCliente) VALUES ('Guardado','$cliente')");
			//Consultar un pedido Guardado de un cliente
			$primerPedido=BD::consulta("SELECT * FROM pedido
			INNER JOIN cliente ON cliente.Usuario=pedido.strCliente
			WHERE Estado='Guardado' AND cliente.Usuario='$cliente'");
			$insertLista=$primerPedido->fetch();
			
			BD::consulta("INSERT INTO lista (codComida,unidad,intIDPedido) VALUES ($idComida,$unidades,$insertLista[0])");
		}
		else{
			//Comprobar la comida de la lista para no repetir y tambien hay saberlo el CIF.
			$comidaIgual=BD::consulta("SELECT * FROM pedido
			INNER JOIN cliente ON cliente.Usuario=pedido.strCliente
			INNER JOIN lista ON lista.intIDPedido=pedido.intIDPedido
			WHERE Estado='Guardado' AND cliente.Usuario='$cliente' AND lista.codComida=$idComida");
			$comida=$comidaIgual->fetch();
			
			if($cifComida[0]==$cif[0]){
				if($comida["codComida"]==$idComida){
					$sumaUnidad=$unidades+$comida["unidad"];
					BD::consulta("UPDATE lista SET unidad=$sumaUnidad WHERE codComida=$idComida AND intIDPedido=$comida[0]");
				}
				else{
					BD::consulta("INSERT INTO lista (codComida,unidad,intIDPedido) VALUES ($idComida,$unidades,$insertSegunda[0])");
				}
			}
			else{
				$alerta="No se puede pedir la comida de diferente restaurante";
			}
			
		}
		return $alerta;
	}
	
	//Mostrar pedidos para el cliente
	public static function mostrarPedidosCliente($cliente){
		$resultado="";
		$sql="SELECT * FROM lista
		INNER JOIN comidas ON comidas.intIDComida=lista.codComida
		INNER JOIN pedido ON pedido.intIDPedido=lista.intIDPedido
		WHERE strCliente='$cliente' AND pedido.Estado='Guardado'";
		if ($resultado_consulta = BD::consulta($sql)){
			while ($registro = $resultado_consulta->fetch()){
				$pedidos = new Pedido($registro);
				$resultado[] = $pedidos;
			}
		}
		else{
			$resultado="No tienes lista de comida";
		}
		return $resultado;
	}
	
	//Mandar pedido
	public function mandarPedido($precio,$order,$bebidas,$alergia,$celiaco,$tipoPago){
		$sql="UPDATE pedido SET tmhora='".date("H:i")."',dtFecha='".date("Y/m/d")."',	dbPrecioTotal=$precio,Estado='Pedido',Bebidas='$bebidas',Alergia='$alergia',Celiacos='$celiaco',TipoDePago='$tipoPago'
		WHERE intIDPedido=$order";
		BD::consulta($sql);
	}
	
	//Mostrar los pedidos
	public static function obtenerPedidos($cliente,$order){
		$resultado="";
		$sql = "SELECT * FROM lista
		INNER JOIN comidas ON comidas.intIDComida=lista.codComida
		INNER JOIN pedido ON pedido.intIDPedido=lista.intIDPedido
		WHERE pedido.Estado='Pedido' AND pedido.strCliente='$cliente' 
		AND pedido.intIDPedido=$order";
		if ($resultado_consulta = BD::consulta($sql)){
			while ($registro = $resultado_consulta->fetch()){
				$pedidos = new Pedido($registro);
				$resultado[] = $pedidos;
			}
		}
		return $resultado;
	}
	
	//Contar los pedidos
	public static function contarPedidos($cif){
		$pedidos=0;
		$sql = "SELECT count(DISTINCT pedido.intIDPedido) FROM pedido
		INNER JOIN lista ON lista.intIDPedido=pedido.intIDPedido
		INNER JOIN comidas ON comidas.intIDComida=lista.codComida
		WHERE Estado IN ('Pedido','Cumplido','Retrasado') AND codCIF='$cif'";
		if ($resultado_consulta = BD::consulta($sql)){
			while ($registro = $resultado_consulta->fetch()){
				$pedidos = $registro [0];
			}
		}
		return $pedidos;
	}
}

?>