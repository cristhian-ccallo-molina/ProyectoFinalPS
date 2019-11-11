<?php 
	require_once "Conexion.php";
	$tipDoc=$_POST["tdc"];
	$numDoc=$_POST["ndc"];
	$razon=$_POST["rzn"];

	$con = new Conexion();
	
	$sql = "INSERT INTO `CLIENTE` (`cod_tipo_doc_usuario`, `num_doc_usuario`, `rzn_social_usuario`) VALUES ('$tipDoc', '$numDoc', '$razon')"; 
	$con->ejecutar($sql);
	header("location: ../index.php");
 ?>