<!DOCTYPE html>
<html lang="en" class="bg-color">

<head>
  
  <title>AMC</title>
  <link rel="stylesheet" href="CSS/style.css">
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>  
  <script src='js/jquery.min.js'></script>
  <script  src="js/index.js"></script>
</head>
<body>
<nav class="bg-color">Factura Electrónica - AMC</nav>
<section class="wrapper">
    <ul class="tabs">
    	<li class="active">Formulario</li>
		<li>Agregar Cliente</li>
		<li>Agregar Producto</li>
	</ul>

	<ul class="tab__content">
		<li class="active">
			<div class="content__wrapper cajon">
				<div class="caja1"><?php
			require_once "Modelo/Detalle.php";
			require_once "Modelo/Data.php";
			require_once "Modelo/boletaCabecera.php";

			session_start();
			if (isset($_SESSION["carrito"])){
				
				$carrito = $_SESSION["carrito"];

				echo "<h3>Detalle de compras</h3>";

				$total = 0;
				$i=0;
				echo "<table border='1'>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Precio</th>
						<th>Stock</th>
						<th>Cantidad</th>
						<th>Subtotal</th>
						
						<th>Eliminar</th>
					</tr>";


				foreach ($carrito as $n) {
					echo"<tr>
						<td>".$n->id."</td>
						<td>".$n->nombre."</td>
						<td>".$n->precio."</td>
						<td>".$n->stock."</td>
						<td>".$n->cantidad."</td>
						<td>".$n->subtotal."</td>
						
						<td><a href='Controlador/eliminarProCar.php?in=$i'>Eliminar</a></td>
					</tr>";
					$total += $n->subtotal;
					$i++;
				}
				echo "</table>";
				echo "Total: ".$total;
				$_SESSION["total"] = $total;

				echo "<br>";
				echo "<br>";
				echo "<div class='cabesera2'>
					<div>
						<label>Total valor de venta  - operaciones gravadas</label>
						<input type='text' name='total_ventas' placeholder='$total' disabled>
					</div>
					<div>
						<label>Sumatoria igv</label>
						<input type='text' name='sum_igv' placeholder='0.00' disabled>
					</div>
					<div>
						<label>Importe total de la venta</label>
						<input type='text' name='imp_tot_venta' placeholder='$total' disabled>
					</div>
				</div>";
				echo "<form action='Controlador/generarBoleta.php' method='post'><input type='submit' value='Comprar'></form>";
			}
		
		echo "<br>
		<a href='Vista/boletas.php'>Lista de Facturas</a><br>
	        <a href='Uploading-files-to-Google-Drive-with-PHP-master/'>Enviar Todos los XML - PDF al repositorio</a><br>
		<br>
		<br>
	</div>




	<div class='caja2'>
		<div class='titulo'>
				<h2>Factura Electrónica</h2>
		</div>";
		
			


			$d = new Data();
			$productos = $d->getProductos();
			$operaciones = $d->tipOperaciones();
			$emisores = $d->localEmisores();
			$nomCli = $d->nombreClientes();
			$tipDocs = $d->tipDocUser();
			$numDocs = $d->numDocUsers();

			$totalCab = count($d->getBoletasCabeceras())+1;
			$totalCab = str_pad($totalCab, 8, "0", STR_PAD_LEFT);
			
			if(isset($_SESSION["cab"])){
				$cab = $_SESSION["cab"];
				$ruc = $_SESSION["ruc"];
				$rzn = $_SESSION["rzn"];

				echo "<div class='cabesera'>
				<div>
					<label>Tipo de operación: </label><label style='color:#000000';> $cab->tipOperacion</label>";
							
			echo "</div>
				<div>
					<label>Numero de RUC: </label><label style='color:#000000';> $ruc</label>
				</div>
				<div>
					<label>Numeración,conformada por serie y número correlativo BF02-1426: </label><label style='color:#000000';> $cab->numBoleta</label>
				</div>
				<div>
					<label>Fecha de Emisión: </label><label style='color:#000000';> $cab->fecha</label>
				</div>
				<div>		
					<label>Nombre y Dirección  del emisor: </label><label style='color:#000000';> $cab->localEmisor</label>";

				echo "</div>
				<div>
					<label>Tipo de documento del usuario: </label><label style='color:#000000';> $cab->tipDocUsuario</label>
				</div>
				<div>
					<label>Numero de documento del usuario: </label><label style='color:#000000';> $cab->docUsuario</label>
				</div>
				<div>
					<label>Apellidos y Nombres/denominación/razón social del usuario: </label><label style='color:#000000';> $rzn</label>";
				echo "</div>
			</div>";
			} else {
			//otro formulario
			echo "<div class='cabesera'>
			<form action='Controlador/guardarCabecera.php' method='post'>
				<div>
					<label>Tipo de operación: </label>";

					// Combo box para tipo de operación (tipo de documento)
					echo "<select name='tipo_doc'>";
					foreach ($operaciones as $p) {
						echo "<option value='$p->id'>$p->id - $p->des</option>";
					}
					echo "</select>";

							
			echo "</div>
				<div>
					<label>Numero de RUC: </label>
					<input type='text' name='num_RUC' maxlength='11' value='10294886504' disabled>
				</div>
				<div>
					<label>Numeración,conformada por serie y número correlativo BF02-1426: </label>
					<input type='text' name='serie_comp' id='codigop1' maxlength='4' value='F002' disabled>-<input type='text' name='numero_comp' id='codigop2'value='$totalCab' disabled maxlength='8'/>
				</div>
				<div>
					<label>Fecha de Emisión: </label>
					<input type='date' name='fecha_emi'>
				</div>
				<div>		
					<label>Nombre y Dirección  del emisor: </label>";


					// Combo box para código y dirección del emisor
					echo "<select name='nombre_dir_emi'>";
					foreach ($emisores as $p) {
						echo "<option value='$p->id'>$p->id - $p->des</option>";
					}
					echo "</select>";	


				echo "</div>
				<div>
					<label>Tipo de documento del usuario: </label>";

					// Combo box para apellidos, nombres, razon social del cliente
					echo "<select name='tip_doc_us'>";
					foreach ($tipDocs as $p) {
						echo "<option value='$p->id'>$p->id - $p->des</option>";
					}
					echo "</select>";

				echo "</div>
				<div>
					<label>Numero de documento del usuario: </label>";

					// Combo box para apellidos, nombres, razon social del cliente
					echo "<select name='num_doc_us'>";
					foreach ($numDocs as $p) {
						echo "<option value='$p->num'>$p->num</option>";
					}
					echo "</select>";

				echo "</div>
				<div>
					<label>Apellidos y Nombres/denominación/razón social del usuario: </label>";


					// Combo box para apellidos, nombres, razon social del cliente
					echo "<select name='ap_nomb_den_razSoc'>";
					foreach ($nomCli as $p) {
						echo "<option value='$p->rzn'>$p->rzn</option>";
					}
					echo "</select>";



				echo "</div>
				<input type='submit' value='Guardar Cabecera'></form>
			</div>";
			}


			echo "<br><table border='1'>
				<tr>
					<th>ID</th>
					<th>Nombre del Producto</th>
					<th>Precio</th>
					<th>Stock</th>
					<th>Añadir a la Boleta</th>
				</tr>";
			foreach ($productos as $p) {
				echo"<tr>
					<td>".$p->id."</td>
					<td>".$p->nombre."</td>
					<td>".$p->precio."</td>
					<td>".$p->stock."</td>
					<td>
						<form action='Controlador/Agregar.php' method='POST'>
							<input type='hidden' name='textId' value='".$p->id."'>
							<input type='hidden' name='textNombre' value='".$p->nombre."'>
							<input type='hidden' name='textPrecio' value='".$p->precio."'>
							<input type='hidden' name='textStock' value='".$p->stock."'>
							<input type='number' name='textCantidad' require='require' placeholder='Cantidad'>
							<input type='submit' name='btnAñadir' value='Añadir al Carrito'>
						</form>
					</td>	
				</tr>";
			}
			echo "</table>";
		?>
		<?php 
			if(isset($_GET["m"])) {
				$m = $_GET["m"];
				switch ($m) {
					case '1':
						echo "El producto no tiene stock.";
						break;
					case '2': 
						echo "La cantidad debe ser número positivo.";
						break;
				}
			}
		?>
	</div>

				
			</div>
		</li>
		<li>
			<div class="content__wrapper">
				<form action="Modelo/insertarCliente.php" method="POST">
					<h2>Cliente</h2>
					<label>Tipo de documento de Cliente:</label>
						<?php 
							require_once "Modelo/Conexion.php";
							$con = new Conexion();

							$query = "select t.cod_tipo_doc_usuario, t.des_tipo_doc_usuario from tipo_doc_identidad t";

							echo "<select name='tdc'>";

							$res = $con->ejecutar($query);
							while ($reg = mysqli_fetch_array($res)) {
								echo "<option value='$reg[0]'>$reg[0] - $reg[1]</option>";
							}
							echo "</select>";
						?>
					<br>
					<label>Número de documento del Cliente:</label>
					<input type="text" name="ndc">
					<br>
					<label>Razón social del Cliente:</label>
					<input type="text" name="rzn">
					<br>
					<input type="submit" value="Agregar" class="">
				</form>
				
			</div>
		</li>
		<li>
			<div class="content__wrapper">
				<form action="Modelo/insertarProducto.php" method="POST">
					<h2>Producto</h2>
					<label>Código del Producto:</label>
					<input type="text" name="cp">
					<br>
					<label>Unidad de medida:</label>
					<input type="text" name="um">
					<br>
					<label>Descripción del item:</label>
					<input type="text" name="di">
					<br>
					<label>Valor unitario:</label>
					<input type="text" name="vu">
					<br>
					<label>Stock:</label>
					<input type="text" name="s">
					<br>
					<label>Tipo de Afectación IGV:</label>
						<?php 
							require_once "Modelo/Conexion.php";
							$con = new Conexion();

							$query = "select t.cod_tip_afe_igv, t.des_tip_afe_igv from tipo_afectacion_igv t";

							echo "<select name='taigv'>";

							$res = $con->ejecutar($query);
							while ($reg = mysqli_fetch_array($res)) {
								echo "<option value='$reg[0]'>$reg[0] - $reg[1]</option>";
							}
							echo "</select>";
						?>
					<br>
					<label>Mto. IGV ítem:</label>
					<input type="text" name="migv">
					<br>
					<input type="submit" value="Agregar" class="">
				</form>
				
			</div>
		</li>
	</ul>
</section>

<footer>AMC</footer>


</body>

</html>				