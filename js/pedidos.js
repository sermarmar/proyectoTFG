$(function(){
	var cliente=$('#cliente').val();
	//Añadir los pedidos
	$('.anadePedido').click(function(){
		var tds=$(this).parent().siblings();
		
		var comida=tds.eq(0).children('input').val();
		var unidades=tds.eq(4).children().val();
		
		$('#load').css({display:"inline"});
		
		$.post("../includes/guardarPedidos.php",{comida:comida,uds:unidades,cliente:cliente},function(datos,estado){
			if(estado=="success"){
				$('#load').css({display:"none"});
				$('#nombreCelda').siblings().remove();
				$('#nombreCelda').after(datos);
				$('#tableDecision').html(datos);
			}
		});
	});
	
	//Borrar los pedidos
	$('#borrarPedidos').click(function(){
		var numPedido=$('.order').val();
		$.post("../includes/borrarLista.php",{numPedido:numPedido},function(datos,estado){
			if(estado="success"){
				$('#nombreCelda').siblings().remove();
				$('#nombreCelda').after(datos);
				$('#tableDecision').html(datos);
			}
		});
	});
	
	//Mostrar los pedidos sin borrar
	$.get("../includes/mostrarPedidoCliente.php",{cliente:cliente},function(datos,estado){
		if(estado=="success"){
			$('#load').css({display:"none"});
			$('#nombreCelda').siblings().children('td').remove();
			$('#nombreCelda').after(datos);
		}
	});

	//Control de unidades
	$('.controlUds').click(function(){
		var tds=$(this).parent().siblings();
		var control=$(this).attr('id');
		
		
		if(control=="menos"){
			var unidades=tds.eq(3).children().val();
			if(unidades>1){
				unidades--;
				tds.eq(3).children().val(unidades);
			}
		}
		else{
			var unidades=tds.eq(4).children().val();
			if(unidades<10){
				unidades++;
				tds.eq(4).children().val(unidades);
			}
		}
	});
	
	//Añadir una tarjeta
	$('.tarjeta').click(function(){
		var tarjeta=$('#TipoDePago2').prop("checked");
		//alert(tarjeta);
		if(tarjeta){
			$('#cartilla').slideDown();
			$('#enviarPedido').attr("disabled",true);
		}
		else{
			$('#cartilla').slideUp();
			$('#enviarPedido').attr("disabled",false);
		}
	});
	
	//Validaciones de la tarjeta
	$('#intTarjeta').blur(function(){
		if(validarNumCuenta()){
			validarTarjeta();
			$('#error').text("");
		}
		else{
			$('#error').text("No es un número de cuenta");
		}
	});
	$('#intTarjeta').keyup(function(){
		if(validarNumCuenta()){
			validarTarjeta();
			$('#error').text("");
		}
	});
	function validarNumCuenta(){
		var numCuenta=$('#intTarjeta').val();
		if(!isNaN(numCuenta) && numCuenta.length==20){
			return true;
		}
		else{
			return false;
		}
	}
	$('#strTarjeta').blur(function(){
		if(validarNombreCuenta()){
			$('#error').text("");
			validarTarjeta();
		}
		else{
			$('#error').text("No es un nombre de cuenta");
		}
		
	});
	$('#strTarjeta').keyup(function(){
		if(validarNombreCuenta()){
			$('#error').text("");
			validarTarjeta();
		}
	});
	function validarNombreCuenta(){
		var strCuenta=$('#strTarjeta').val();
		if(isNaN(strCuenta) && strCuenta.length>=10){
			return true;
		}
		else{
			return false;
		}
	}
	$('#mes').change(function(){
		if(validarFecha()){
			$('#error').text("");
			validarTarjeta();
		}
		else{
			$('#error').text("La tarjeta está caducada");
		}
	});
	$('#anio').change(function(){
		if(validarFecha()){
			$('#error').text("");
			validarTarjeta();
		}
		else{
			$('#error').text("La tarjeta está caducada");
		}
	});
	function validarFecha(){
		var mes=$('#mes').val();
		var anio=$('#anio').val();
		var fecha=new Date();
		var mesActual=fecha.getMonth();
		var anioActual=fecha.getFullYear();
		if(mes>mesActual && anio==anioActual){
			return true;
		}
		else if(anio>anioActual){
			return true;
		}
		else{
			return false;
		}
	}
	$('#CVV').blur(function(){
		if(validarCVV()){
			$('#error').text("");
			validarTarjeta();
		}
		else{
			$('#error').text("Este número no es correcto");
		}
	});
	$('#CVV').keyup(function(){
		if(validarCVV()){
			$('#error').text("");
			validarTarjeta();
		}
	});
	function validarCVV(){
		var cvv=$('#CVV').val();
		if(!isNaN(cvv) && cvv.length==3){
			return true
		}
		else{
			return false;
		}
	}
	
	function validarTarjeta(){
		if(validarNumCuenta() && validarNombreCuenta() && validarFecha() && validarCVV()){
			$('#enviarPedido').attr("disabled",false);
		}
		else{
			$('#enviarPedido').attr("disabled",true);
		}
	}
	
	//Actualización de pedidos en tiempo real.
	setInterval(function(){cargarPedidos()}, 3000);
	
	function cargarPedidos(){
		$.get("../includes/cargarPedidos.php",function(datos,estado){
			if(estado=="success"){
				$('#cajaPedido').html(datos);
			}
		});
	}
});