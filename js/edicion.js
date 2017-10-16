$(function(){
	
	$('#nombreComida').blur(function(){
		validarNombre();
		validar();
	});
	function validarNombre(){
		var valor=$('#nombreComida').val();
		if(valor.length<5 && isNaN(valor) || valor==""){
			$('#nombreComida').css({
				backgroundColor:"#ff7a7a"
			});
			$('#nombre').text("Mínimo de 5 carácteres.");
			$('#nombre').show();
			return false;
		}
		else{
			$('#nombreComida').css({
				backgroundColor:"white"
			});
			$('#nombre').hide();
			return true;
		}
	}
	
	$('#precioComida').blur(function(){
		validarPrecio();
		validar();
	});
	function validarPrecio(){
		var valor=$('#precioComida').val();
		var precio=valor.replace(",",".");
		if(isNaN(precio) || precio==""){
			$('#precioComida').css({
				backgroundColor:"#ff7a7a"
			});
			$('#precio').text("Tiene que ser un número.");
			$('#precio').show();
			return false;
		}
		else{
			$('#precioComida').css({
				backgroundColor:"white"
			});
			$('#precio').hide();
			return true;
		}
	}
	
	$('#ingredientesComida').blur(function(){
		validarIngredientes();
		validar();
	});
	function validarIngredientes(){
		var valor=$('#ingredientesComida').val().split(',');
		if(valor.length<3){
			$('#ingredientesComida').css({
				backgroundColor:"#ff7a7a"
			});
			$('#ingredientes').text("Tiene que tener más 3 ingredientes");
			$('#ingredientes').show();
			return false;
		}
		else{
			$('#ingredientesComida').css({
				backgroundColor:"white"
			});
			$('#ingredientes').hide();
			return true;
		}
	}
	
	function validar(){
		if(validarIngredientes() && validarPrecio() && validarNombre()){
			$('#anadeComida').removeAttr("disabled");
			//alert("Funciona");
		}
		else{
			$('#anadeComida').prop("disabled",true);
			//alert("No Funciona");
		}
	}
	
	$('.publicar').click(function(){
		var id=$(this).siblings().val();
		var boton=$(this);
		var publico=$(this).val();
		$.post("../includes/publicar.php",{id:id,publico:publico},function(datos,estado){
			if(estado=="success"){
				boton.val(datos);
			}
		});
	});
	
	//Borrar comidas
	$('.borrar').click(function(){
		var id=$(this).parent().siblings().children('#idOculto').val();
		var obj=$(this);
		
		$.post("../includes/borrarComida.php",{id:id},function(datos,estado){
			obj.parents('tr').hide();
		});
	});
	
	//Editar comidas
	$('.edicion').keyup(function(){
		$(this).css({
			backgroundColor:"#ffd685"
		});
		$.post("../includes/modificarInput.php",{celda:celda,valor:valor},function(datos,estado){
			if(estado=="success"){
				
			}
		});
	});
	$('.edicion').blur(function(){
		var input=$(this);
		var celda=input.attr("id");
		var valor=input.val();
		var id=input.parents('tr').children('.idComida').val();
		$.post("../includes/modificarInput.php",{id:id,celda:celda,valor:valor},function(datos,estado){
			if(estado=="success"){
				input.css({
					backgroundColor:"#9fff85"
				});
			}
		});
	});
	
});