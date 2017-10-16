<?php

require_once("../clases/BD.php");

$id=$_POST["id"];

BD::consulta("DELETE FROM comidas WHERE intIDComida=$id");


?>