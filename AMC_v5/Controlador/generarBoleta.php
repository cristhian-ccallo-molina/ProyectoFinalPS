<?php 
	require_once "../Modelo/Data.php";
	session_start();

	$carrito = $_SESSION["carrito"];
	$total = $_SESSION["total"];
	$cab = $_SESSION["cab"];

	$d = new Data();
	$d->crearBoleta($carrito, $total, $cab);

	// remover el carrito de compras
	unset($_SESSION["carrito"]);
	//unset($_SESSION["total"]);
	unset($_SESSION["cab"]);
	
	header("location: ../generarXML.php");
 ?>