<?php 
    require_once "../Modelo/Data.php";
	require_once "../Modelo/boletaCabecera.php";

	$c = new boletaCabecera();
	$c->id = "0";
	$c->tipOperacion = $_POST["tipo_doc"];
	$c->numBoleta = $_POST["serie_comp"]."-".$_POST["numero_comp"];
	$c->fecha = $_POST["fecha_emi"];
	$c->localEmisor = $_POST["nombre_dir_emi"];
	$c->tipDocUsuario = $_POST["tip_doc_us"];
	$c->docUsuario = $_POST["num_doc_us"];
	$c->tipMoneda = "PEN";
	$c->total = "0";
	$c->sumIGV = "0";
	$c->impTotal = "0";

	//$ruc = $_POST["num_RUC"];
	$numRuc = "10294886504";
	$rzn = $_POST["ap_nomb_den_razSoc"];

	session_start();
	if(isset($_SESSION["cab"])){
		$cab = $_SESSION["cab"];
	} else {
		$cab = $c;
	}
	$_SESSION["cab"] = $cab;

	if(isset($_SESSION["ruc"])){
		$ruc = $_SESSION["ruc"];
	} else {
		$ruc = $numRuc;
	}
	$_SESSION["ruc"] = $ruc;

	if(isset($_SESSION["rzn"])){
		$r = $_SESSION["rzn"];
	} else {
		$r = $rzn;
	}
	$_SESSION["rzn"] = $r;

	//echo $c->tipOperacion." - ".$c->fecha;
	header("location: ../index.php");
 ?>