$(function(){
	//$('.next').attr("disabled", true);
	//$('#enviar').attr("disabled",true);
	var registro=$('#registro').val();
	//Primera cara
	//Validación de usuario
	$("#usuario").blur(function(){
		if(!validacionUsuario()){
			$("#error").show();
			$(this).css({
				backgroundColor:"#ff7a7a"
			});
		}
		else{
			$('#error').hide();
		}
	});
	$("#usuario").keyup(function(){
		if(validacionUsuario()){
			$(this).css({
				backgroundColor:"white"
			});
		}
	});
	
	function validacionUsuario(){
		var usuario=$("#usuario").val();
		if(jQuery.type(usuario)!=="undefined"){
			if(usuario.length<6){
				$('#error').text("Mínimo 6 de longitud de usuario");
				return false;
			}
			else{
				$('#error').hide();
				return true;
			}
		}
		/*if(jQuery.type(usuario)!=="undefined"){
			if(usuario.length<6){
				$('#error').text("Mínimo 6 de longitud de usuario");
				return false;
			}
			else{
				$.get("../includes/repetidos.php",{usuario:usuario},function(datos,estados){
					if(estados=="success"){
						if(datos==usuario){
							$('#error').text("Este nombre de usuario ya existe");
							//alert(datos);
							return false;
							
						}
						else{
							return true;
						}
					}
				});
			}
		}*/
		/*$.get("../includes/repetidos.php",{usuario:usuario},function(datos,estados){
			if(estados=="success"){
				if(datos==usuario){
					$('#error').text("Este nombre de usuario ya existe");
					return false;

				}
				else{
					return true;
				}
			}
		});*/
	}
	
	//Validacion de CIF
	$("#cif").blur(function(){
		if(validarCIF()){
			$('#error').hide();
		}
		else{
			$('#error').text("Esto no es un CIF, escribe correctamente, por favor.");
			$('#error').show();
			$(this).css({
				backgroundColor:"#ff7a7a"
			})
		}
	});
	$("#cif").keyup(function(){
		if(validarCIF()){
			$('#error').hide();
			$(this).css({
				backgroundColor:"white"
			})
		}
	});
	function validarCIF(){
		var cif=$('#cif').val();
		if(jQuery.type(cif)!=="undefined"){
			var valueCif=cif.substr(1,cif.length-2);

			var suma=0;

			//Sumamos las cifras pares de la cadena
			for(i=1;i<valueCif.length;i=i+2)
			{
				suma=suma+parseInt(valueCif.substr(i,1));
			}

			var suma2=0;

			//Sumamos las cifras impares de la cadena
			for(i=0;i<valueCif.length;i=i+2)
			{
				result=parseInt(valueCif.substr(i,1))*2;
				if(String(result).length==1)
				{
					// Un solo caracter
					suma2=suma2+parseInt(result);
				}else{
					// Dos caracteres. Los sumamos...
					suma2=suma2+parseInt(String(result).substr(0,1))+parseInt(String(result).substr(1,1));
				}
			}

			// Sumamos las dos sumas que hemos realizado
			suma=suma+suma2;

			var unidad=String(suma).substr(1,1)
			unidad=10-parseInt(unidad);

			var primerCaracter=cif.substr(0,1).toUpperCase();

			if(primerCaracter.match(/^[FJKNPQRSUVW]$/))
			{
				//Empieza por .... Comparamos la ultima letra
				if(String.fromCharCode(64+unidad).toUpperCase()==cif.substr(cif.length-1,1).toUpperCase())
					return true;
			}else if(primerCaracter.match(/^[XYZ]$/)){
				//Se valida como un dni
				var newcif;
				if(primerCaracter=="X")
					newcif=cif.substr(1);
				else if(primerCaracter=="Y")
					newcif="1"+cif.substr(1);
				else if(primerCaracter=="Z")
					newcif="2"+cif.substr(1);
				return validateDNI(newcif);
			}else if(primerCaracter.match(/^[ABCDEFGHLM]$/)){
				//Se revisa que el ultimo valor coincida con el calculo
				if(unidad==10)
					unidad=0;
				if(cif.substr(cif.length-1,1)==String(unidad))
					return true;
			}
			return false;
		}
		
	}
	
	//Validacion de correo electrónico
	$("#email").focusout(function(){
		if(!validacionEmail()){
			$('#error').text("El correo electrónico introducido no es correcto.");
			$('#error').show();
			$(this).css({
				backgroundColor:"#ff7a7a"
			});
		}	
	});
	$("#email").keyup(function(){
		if(validacionEmail()){
			$(this).css({
				backgroundColor:"white"
			});
		}
	});
	
	function validacionEmail(){
		if($("#email").val().indexOf('@', 0) == -1 || $("#email").val().indexOf('.', 0) == -1) {
			$('#email').focus();
			return false;
        }
		else{
			$('#error').hide();
			return true;
		}
	}
	
	//Validacion de contraseña
	$('#pass').keyup(function(){
		validarPass();
	});
	$('#pass').focusin(function(){
		$('#balloon').slideDown();
	});
	$('#pass').focusout(function(){
		$('#balloon').slideUp();
	});
	
	function validarPass(){
		var pass=$('#pass').val();
		var correcto=false;
		if(pass.length>=8){
			$('#longitud').css({color:"green"});
			correcto=true;
		}
		else{
			$('#longitud').css({color:"#C50801"});
			correcto=false;
		}
		if(pass.match(/[A-z]/)){
			$('#letrasMin').css({color:"green"});
			correcto=true;
		}
		else{
			$('#letrasMin').css({color:"#C50801"});
			correcto=false;
		}
		if(pass.match(/[A-Z]/)){
			$('#letrasMay').css({color:"green"});
			correcto=true;
		}
		else{
			$('#letrasMay').css({color:"#C50801"});
			correcto=false;
		}
		if(pass.match(/\d/)){
			$('#digitos').css({color:"green"});
			correcto=true;
		}
		else{
			$('#digitos').css({color:"#C50801"});
			correcto=false;
		}
		return correcto;
	}
	
	//Repite la contraseña
	$('#pass2').focusout(function(){
		if(!validarPass2()){
			$('#error').text("La contraseña no es igual a la anterior");
			$('#error').show();
			$(this).css({
				backgroundColor:"#ff7a7a"
			})
		}
	});
	$('#pass2').keyup(function(){
		if(validarPass2()){
			$(this).css({
				backgroundColor:"white"
			})
		}
	});
	function validarPass2(pass, pass2){
		var pass=$('#pass').val();
		var pass2=$('#pass2').val();
		if(pass!=pass2){
			$('#pass2').focus();
			return false;
		}
		else{
			$('#error').hide();
			return true;
		}
	}
	//Botón de validar
	var c=1;
	$('#unoRegister').click(function(){
		if((validacionUsuario()|| validarCIF()) && validacionEmail() && validarPass() && validarPass2()){
			$('#slider1').animate({marginLeft:'-2000px'});
			c++;
		}
		else{
			$('#slider1').animate({marginLeft:'0px'});
		}
	});
	
	
	//Segunda cara
	//Validación de nombre
	$('#nombre').focusout(function(){
		if(!validacionNombre()){
			$('#error').text("La longitud del nombre tiene que ser mínimo 3 y máximo 15");
			$('#error').show();
			$(this).css({
				backgroundColor:"#ff7a7a"
			})
		}
	});
	$('#nombre').keyup(function(){
		if(validacionNombre()){
			$(this).css({
				backgroundColor:"white"
			})
		}
	});
	function validacionNombre(){
		var nombre=$('#nombre').val();
		if(jQuery.type(nombre)!=="undefined" && nombre.length>=3 && nombre.length<=15){
			$('#error').hide();
			return true;
		}
		else{
			$('#nombre').focus();
			return false;
		}
	}
	
	//Validación de apellidos
	$('#apellidos').focusout(function(){
		if(!validacionApellidos()){
			$('#error').text("La longitud del nombre tiene que ser mínimo 6 y máximo 25");
			$('#error').show();
			$(this).css({
				backgroundColor:"#ff7a7a"
			})
		}
	});
	$('#apellidos').keyup(function(){
		if(validacionApellidos()){
			$(this).css({
				backgroundColor:"white"
			})
		}
	});
	function validacionApellidos(){
		var apellidos=$('#apellidos').val();
		if(apellidos.length>=6 && apellidos.length<=25){
			$('#error').hide();
			return true;
		}
		else{
			$('#apellidos').focus();
			return false;
		}
	}
	
	//Validación de fecha de Nacimiento
	$('#anio').change(function(){
		if(validarFecha()){
			$(this).css({
				backgroundColor:"white"
			})
		}
		else{
			$('#error').text("No es para menores de 18 años.");
			$('#error').show();
			$(this).css({
				backgroundColor:"#ff7a7a"
			})
		}
	});
	function validarFecha(){
		var anio=$('#anio').val();
		var fecha=new Date();
		var anioActual=fecha.getFullYear()-18;
		if(anio<=anioActual){
			$('#error').hide();
			return true;
		}
		else{
			return false;
		}
	}

	//Para el siguiente la cara
	$('#dosRegister').click(function(){
		if(validacionNombre() && validacionApellidos() && validarFecha()){
			$('#slider2').animate({marginLeft:'-2000px'});
			c++;
		}
		else{
			$('#slider2').animate({marginLeft:'0px'});
		}
	});
	
	//Tercera Cara
	
	//Validacion del Nombre de Restaurante
	$('#restaurante').focusout(function(){
		if(validarRestaurante()){
			validar3();
		}
		else{
			$('#error').text("La longitud del restaurante tiene que ser mínimo 4 carácteres.");
			$('#error').show();
			$(this).css({
				backgroundColor:"#ff7a7a"
			})
		}
	});
	$('#restaurante').keyup(function(){
		if(validarRestaurante()){
			validar3();
			$(this).css({
				backgroundColor:"white"
			})
		}
	});
	function validarRestaurante(){
		var restaurante=$('#restaurante').val();
		if(jQuery.type(restaurante)!=="undefined"){
			if(restaurante.length>=4){
				$('#error').hide();
				return true;
			}
			else{
				return false;
			}
		}
	}
	
	//Validacion de Localidad
	$('#localidad').keyup(function(){
		var localidad=$(this).val();
		
		if(localidad.length>=3){
			$.get("../includes/obtenerLocalidad.php",{localidad:localidad},function(datos,estado){
				if(estado=="success"){
					$('.eligeLocalidad').html(datos);
					$('.eligeLocalidad').slideDown();
				}//alert(localidad);
			});
		}
		else{
			$('.eligeLocalidad').slideUp();
		}
	});
	
	$('#localidad').focusout(function(){
		if(validarLocalidad()){
			validar3();
		}
		else{
			$('#error').text("Escoge la localidad.");
			$('#error').show();
		}
	});
	function validarLocalidad(){
		var localidad=$('#localidad').val();
		if(localidad.length>=4){
			$('#error').hide();
			return true;
		}
		else{
			return false;
		}
	}
	
	//Validacion de Localidad
	$('#intTelefono').focusout(function(){
		if(validarTelefono()){
			validar3();
		}
		else{
			$('#error').text("El número de telefono no es correcto");
			$('#error').show();
			$(this).css({
				backgroundColor:"#ff7a7a"
			})
		}
	});
	$('#intTelefono').keyup(function(){
		if(validarTelefono()){
			validar3();
			$(this).css({
				backgroundColor:"white"
			})
		}
	});
	function validarTelefono(){
		var telefono=$('#intTelefono').val();
		if(!isNaN(telefono) && (telefono>=600000000 && telefono<=699999999) || (telefono>=900000000 && telefono<=999999999)){
			$('#error').hide();
			return true;
		}
		else{
			return false;
		}
	}
	
	//Validar de Direccion
	$('#direccion').focusout(function(){
		if(validarDireccion()){
			validar3();
		}
		else{
			$('#error').text("La longitud de direccion tiene que ser mínimo 10 carácteres.");
			$('#error').show();
			$(this).css({
				backgroundColor:"#ff7a7a"
			});
		}
	});
	$('#direccion').keyup(function(){
		if(validarDireccion()){
			validar3();
			$(this).css({
				backgroundColor:"white"
			});
		}
	});
	function validarDireccion(){
		var direccion=$('#direccion').val();
		if(direccion.length>=10){
			$('#error').hide();
			return true;
		}
		else{
			return false;
		}
	}
	
	//Validar de Numero
	$('#numero').focusout(function(){
		if(validarNumero()){
			validar3();
		}
		else{
			$('#error').text("No debería estar vacío o tiene que ser un número");
			$('#error').show();
			$(this).css({
				backgroundColor:"#ff7a7a"
			})
		}
	});
	$('#numero').keyup(function(){
		if(validarNumero()){
			validar3();
			$(this).css({
				backgroundColor:"white"
			});
		}
	});
	function validarNumero(){
		var numero=$('#numero').val();
		if(numero.length>0){
			$('#error').hide();
			return true;
		}
		else{
			return false;
		}
	}
	
	//Validar Codigo Postal
	$('#codPostal').focusout(function(){
		if(validarCP()){
			validar3();
		}
		else{
			$('#error').text("La longitud de CP es 5 carácteres y tiene que ser números");
			$('#error').show();
			$(this).css({
				backgroundColor:"#ff7a7a"
			})
		}
	});
	$('#codPostal').keyup(function(){
		if(validarCP()){
			validar3();
			$(this).css({
				backgroundColor:"white"
			});
		}
	});
	function validarCP(){
		var CP=$('#codPostal').val();
		if(CP.length==5 && !isNaN(CP)){
			$('#error').hide();
			return true;
		}
		else{
			return false;
		}
	}
	
	function validar3(){
		if(registro=="cliente"){
			if(validarLocalidad() && validarTelefono() && validarDireccion() && validarNumero() && validarCP()){
				$('#enviar').attr("type", "submit");
			}
			else{
				$('#enviar').attr("type", "button");
			}
		}
		else{
			if(validarRestaurante() && validarLocalidad() && validarTelefono() && validarDireccion() && validarNumero() && validarCP()){
				$('#enviar').attr("type", "submit");
			}
			else{
				$('#enviar').attr("type", "button");
			}
		}
		
	}
	
	//Para marcha atras
	$('.back').click(function(){
		c--;
		$('#slider'+c).animate({marginLeft:'0px'});
	});
	
});
		
		
		
		
		
		
		
		
		
		