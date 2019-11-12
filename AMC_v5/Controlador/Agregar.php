<?php 
	require_once "../Modelo/Detalle.php";
	echo "entra aqui";
	$p = new Detalle();
	$p->id = $_POST["textId"];
	$p->nombre = $_POST["textNombre"];
	$p->precio = $_POST["textPrecio"];
	$p->stock = $_POST["textStock"];
	$p->cantidad = $_POST["textCantidad"];
	$p->subtotal = $p->precio * $p->cantidad;
	echo "entra aqui";

	if ($p->cantidad > 0) {
		session_start();
		if(isset($_SESSION["carrito"])){
			$carrito = $_SESSION["carrito"];
		} else {
			$carrito = array();
		}

		$sumaCantidades = 0;
		foreach ($carrito as $pro) {
			if ($pro->id == $p->id){
				$sumaCantidades += $pro->cantidad;
			}
		}
		$sumaCantidades += $p->cantidad;
		if($p->stock >= $sumaCantidades) {
			// hay stock
			array_push($carrito, $p);
			$_SESSION["carrito"] = $carrito;

			header("location: ../index.php");
		} else {
			// no tiene stock
			header("location: ../index.php?m=1");
		}
	} else {
		// cantidad negativa
		header("location: ../index.php?m=2");
	}
 ?>