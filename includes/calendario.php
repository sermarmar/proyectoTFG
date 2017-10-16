<?php

$mes=date("n");
$anio=date("Y");
$hoy=date("j");
?>

<td>Fecha Nacimiento:</td>

<td><select class="form-control" name="anio" id="anio">
<?php
for($i=$anio;$i>=$anio-100;$i--){?>
	<option value="<?=$i?>"><?=$i?></option>;
<?php }

?>
</select></td>

<td><select class="form-control" name="mes" id="mes">
<?php

$mesString=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

for($i=1;$i<count($mesString);$i++){?>
	<option value="<?=$i?>" <?php if($i+1==$mes){echo "selected";}?> ><?=$mesString[$i]?></option>;
<?php }

?>
</select></td>

<td><select class="form-control" name="dia" id="dia">
<?php
if($mes==2 && $anio%2!=0){
	for($dia=1;$dia<=28;$dia++){?>
		<option value="<?=$dia?>" <?php if($dia==$hoy){echo "selected";}?>><?=$dia?></option>;
	<?php }
}
else if($mes==2 && $anio%2==0){
	for($dia=1;$dia<=29;$dia++){?>
		<option value="<?=$dia?>" <?php if($dia==$hoy){echo "selected";}?>><?=$dia?></option>";
	<?php }
}
else if(($mes%2==0 && $mes<8) || ($mes%2!=0 && $mes>8)){
	for($dia=1;$dia<=30;$dia++){?>
		<option value="<?=$dia?>" <?php if($dia==$hoy){echo "selected";}?>><?=$dia?></option>;
	<?php }
}
else{
	for($dia=1;$dia<=31;$dia++){?>
		<option value="<?=$dia?>" <?php if($dia==$hoy){echo "selected";}?>><?=$dia?></option>;
	<?php }
}


?>
</select></td>










