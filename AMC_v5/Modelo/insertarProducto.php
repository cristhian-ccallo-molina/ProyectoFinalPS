<?php 
	require_once "Conexion.php";
	$codpro=$_POST["cp"];
	$uniMed=$_POST["um"];
	$desItem=$_POST["di"];
	$valUni=$_POST["vu"];
	$stock=$_POST["s"];
	$mtoIgv=$_POST["migv"];
	$tipAfe=$_POST["taigv"];

	$con = new Conexion();
	
	$sql = "INSERT INTO `PRODUCTO` (`cod_producto`, `cod_producto_sunat`, `cod_unidad_medida`, `des_item`, `mto_valor_unitario`, `stock`, `mto_igv_item`, `cod_tip_afe_igv`) VALUES ('$codpro', '0', '$uniMed', '$desItem', '$valUni', '$stock', '$mtoIgv', '$tipAfe')"; 
	$con->ejecutar($sql);
	header("location: ../index.php");
 ?>