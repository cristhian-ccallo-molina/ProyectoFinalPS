<!DOCTYPE html>
<html>
<head>
	<title>Listado de Boletas</title>
</head>
<body>
	<?php 
		require_once "../Modelo/Data.php";
		$d = new Data ();
		$boletasCabeceras = $d->getBoletasCabeceras();

		echo "<h2>Listado de Facturas</h2>";

		echo "<table border='1'>";
			echo "<tr>";
				echo "<th>ID</th>";
				echo "<th>Tipo de Operación</th>";
				echo "<th>Número de Factura</th>";
				echo "<th>Fecha</th>";
				echo "<th>Local del Emisor</th>";
				echo "<th>Tipo de Doc. de Usuario</th>";
				echo "<th>Número de Doc. de Usuario</th>";
				echo "<th>Tipo de moneda</th>";
				echo "<th>Total</th>";
				echo "<th>Sumatoria IGV</th>";
				echo "<th>Importe Total</th>";
				echo "<th>Detalle</th>";
			echo "</tr>";

		foreach ($boletasCabeceras as $k) {
			echo "<tr>";
				echo "<td>".$k->id."</td>";
				echo "<td>".$k->tipOperacion."</td>";
				echo "<td>".$k->numBoleta."</td>";
				echo "<td>".$k->fecha."</td>";
				echo "<td>".$k->localEmisor."</td>";
				echo "<td>".$k->tipDocUsuario."</td>";
				echo "<td>".$k->docUsuario."</td>";
				echo "<td>".$k->tipMoneda."</td>";
				echo "<td>".$k->total."</td>";
				echo "<td>".$k->sumIGV."</td>";
				echo "<td>".$k->impTotal."</td>";

				echo "<td>";
					echo "<a href='detalles.php?id=".$k->id."'>Ver Detalles</a>";
				echo "</th>";
			echo "</tr>";
		}
		echo "</table>";
		echo "<a href='../index.php'>Volver</a>";
	 ?>
</body>
</html>