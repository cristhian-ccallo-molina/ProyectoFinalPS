<?php 
	require_once "../Modelo/Data.php";

	$idBoleta = $_GET["id"];

	$d = new Data();

	$detalles = $d->getDetalles($idBoleta);
	echo "<h2>Detalles de Boleta ID: $idBoleta</h2>";

	echo "<table border='1'>";
		echo "<tr>";
			echo "<th>ID</th>";
			echo "<th>Producto</th>";
			echo "<th>Precio Unitario</th>";
			echo "<th>Cantidad</th>";
			echo "<th>Subtotal</th>";
			echo "<th>Monto IGV</th>";
			echo "<th>Total del Detalle</th>";
		echo "</tr>";

	$total = 0;
	foreach ($detalles as $k) {
		echo "<tr>";
			echo "<td>".$k->id."</td>";
			echo "<td>".$k->nomProducto."</td>";
			echo "<td>".$k->precio."</td>";
			echo "<td>".$k->cantidad."</td>";
			echo "<td>".$k->precio * $k->cantidad."</td>";
			echo "<td>".$k->mtoIGV."</td>";
			echo "<td>".$k->totalDetalle."</td>";
			$total += $k->totalDetalle;
			echo "</td>";
		echo "</tr>";
	}
	echo "<tr>";
		echo "<td colspan='6'><b>Total</b></td>";
		echo "<td><b>$total</b></td>";
	echo "</tr>";
	echo "</table>";
	echo "<br>";
	echo "<br>";
	echo "<a href='../Vista/boletas.php'>Volver a Boletas</a>";
 ?>