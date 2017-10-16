$(function(){
	
	//Para la portada
	var pos=0;
	var winPos=0;
	$(window).scroll(function(){
		$(".slides").each(function(){
			var pos=$(this).offset().top;
			var winPos=$(window).scrollTop();
			if(pos < winPos+600){
				$(this).animate({marginLeft:0,opacity:1},1000);
			}
		});
	});
	$(document).ready(function(){
		$('[data-toggle="popover"]').popover();   
	});
	
	//Para buscar el restaurante
	/*$('#buscador').keyup(function(){
		var valor=$(this).val();
		
		$.get("../includes/buscarRestaurante.php",{valor:valor},function(datos,estado){
			if(estado=="success"){
				$('#accordion').html(datos);
			}
		});
	});*/
	
	//Para el cambio del mes y del año
	var mes=parseInt($('#mes').val())+1;
	var anio=$('#anio').val();
	
	$('#dia').html(calendario(mes,anio));
	$('#anio').change(function(){
		anio=$('#anio').val();
		$('#dia').html(calendario(mes,anio));
	});
	$('#mes').change(function(){
		mes=parseInt($('#mes').val())+1;
		$('#dia').html(calendario(mes,anio));
	});
	
	//Boton de movimiento del un registro
	var i=1;
	/*$('.next').click(function(){
		$('#slider'+i).animate({marginLeft:'-2000px'});
		i++;
	});*/
	/*$('.back').click(function(){
		i--;
		$('#slider'+i).animate({marginLeft:'0px'});
	});*/
	
	//Para el panel de administrador
	var altura=$(window).height();
	var ancho=$(window).width();
	$('#fixed .panel').height(altura);
	
	//Subir fotos
	$('#logofoto').change(function(){
		var foto=$(this).val();
		alert(foto);
	});
	
	
});

function calendario(mes,anio){
	var dias="";
	if(mes==2 && anio%2!=0){
		for(var dia=1;dia<=28;dia++){
			dias+="<option value="+dia+">"+dia+"</option>";
		}
	}
	else if(mes==2 && anio%2==0){
		for(dia=1;dia<=29;dia++){
			dias+="<option value="+dia+">"+dia+"</option>";
		}
	}
	else if((mes%2==0 && mes<8) || (mes%2!=0 && mes>8)){
		for(dia=1;dia<=30;dia++){
			dias+="<option value="+dia+">"+dia+"</option>";
		}
	}
	else{
		for(dia=1;dia<=31;dia++){
			dias+="<option value="+dia+">"+dia+"</option>";
		}
	}
	return dias;
}