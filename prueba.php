<?php


?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Prueba de fotos</title>
</head>

<body>
<form action="subirfoto.php" enctype="multipart/form-data" method="POST">
	<input type="file" name="foto">
	<input type="submit" name="enviar" value="Subir la foto">
</form>
</body>
</html>