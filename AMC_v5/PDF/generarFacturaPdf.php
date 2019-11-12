<?php

require_once "../Modelo/boletaCabecera.php";
require_once "../Modelo/Detalle.php";
require_once "../Modelo/Data.php";

echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" />';
echo '<h2>Facturación electrónica SUNAT</h2><br>';
//header("Content-Type: text/html; charset=UTF-8");

session_start();
$c = new boletaCabecera();
$c = $r = $_SESSION["cab"];

//Recibir detalles de boleta
$tip_op = $c->tipOperacion;
$ruc_empresa = $_SESSION["ruc"];
$num_boleta = $_SESSION["nomArchivo"];
$fecha_boleta = $c->fecha;
$nomb_dir_emi = $c->localEmisor;
$tip_doc_us = $c->tipDocUsuario;
$raz_social = $_SESSION["rzn"];

$d = new Data();
$local = $d->localEmisores();
$a = "";
foreach ($local as $p) {
	if ($nomb_dir_emi == $p->id) {
		$a = $p->des;
		break;
	}
}
$nomb_dir_emi = $nomb_dir_emi." - ".$a;

$dd = $d->tipDocUser();
$b = "";
foreach ($dd as $p) {
	if ($tip_doc_us == $p->id) {
		$b = $p->des;
		break;
	}
}
$tip_doc_us = $tip_doc_us." - ".$b;

//Recibir los datos del cliente
$razon_cliente = $_SESSION["rzn"];
$tip_doc_cliente = $tip_doc_us;
$direccion_cliente = $nomb_dir_emi;
$num_doc_cliente = $c->docUsuario;

echo '<div style="font-family: Arial; font-size: 12pt; color: #000000; margin-top: 10px;">';
echo 'Archivo .XML enviado a la SUNAT, nos retornó una constancia de recepción:<br>';
echo '<span style="color: red;">R-'.$num_boleta.'.xml</span>';
echo '</div>';
echo "<a target='_blank' href='../Uploading-files-to-Google-Drive-with-PHP-master/files/R-$num_boleta.xml'>Ver XML de Aceptación</a><br><br>";

//Recibir los datos de los productos
/*$cod_producto = $_POST["cod_producto"];
$desc_prod = $_POST["desc_prod"];
$uni_med = $_POST["uni_med"];
$val_unit = $_POST["val_unit"];
$stock_prod = $_POST["stock_prod"];
$tip_afec = $_POST["tip_afec"];
$monto_igv = $_POST["monto_igv"];*/

//variable que guarda el nombre del archivo PDF
$archivo="PDF-$num_boleta.pdf";

//Llamada al script fpdf
require('fpdf.php');

$archivo_de_salida = $archivo;

$pdf=new FPDF();  //crea el objeto
$pdf->AddPage();  //añadimos una página. Origen coordenadas, esquina superior izquierda, posición por defeto a 1 cm de los bordes.


// Encabezado de la boleta
$pdf->SetFont('Arial','B',14);
$pdf->Ln(2);
$pdf->SetTextColor(255,0,0);
$pdf->Cell(70, 5, "R.U.C. $ruc_empresa", 1, 0, "C");
$pdf->Ln();
$pdf->SetTextColor(0,0,0);
$pdf->Cell(70, 5, utf8_decode("FACTURA ELECTRÓNICA"), 1, 0,"C");
$pdf->Ln();
$pdf->SetTextColor(0,0,255);
$pdf->Cell(70, 5, "$num_boleta", 1, 0, "C");
$pdf->Ln();

$pdf->SetTextColor(0,0,0);
//encabezado
$pdf->Image('../logoAMC.jpg' , 80, 10, 120 , 40,'JPG');
$pdf->Ln(25);
$pdf->MultiCell(255, 5, "$raz_social"."\n"."$nomb_dir_emi"."\n"."$tip_doc_us", 0, "C");	
$pdf->Ln();

$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(85, 15, "Cliente: $razon_cliente"."\n".utf8_decode("Dirección: ")."$direccion_cliente"."\n"."Documento Nro: $num_doc_cliente"."\n"."Fecha: $fecha_boleta", 0, "L");
$pdf->Ln(3);

$top_productos = 140;
	
    $pdf->SetXY(15, $top_productos);
    $pdf->Cell(10, 5, 'N°', 1, 1, 'C');
	$pdf->SetXY(25, $top_productos);
    $pdf->Cell(40, 5, utf8_decode("DESCRIPCIÓN"), 1, 1, 'C');
    $pdf->SetXY(65, $top_productos);
    $pdf->Cell(40, 5, 'PRECIO UNITARIO', 1, 1, 'C');
    $pdf->SetXY(105, $top_productos);
    $pdf->Cell(50, 5, 'CANTIDAD', 1, 1, 'C');
	$pdf->SetXY(155, $top_productos);
    $pdf->Cell(40, 5, 'SUBTOTAL', 1, 1, 'C');   
	$pdf->Ln(3);

	$x = 1;
	$y = 140;

	$carrito = $_SESSION["carrito"];
	$pdf->SetTextColor(100,100,100);
	foreach($carrito as $n){
		$y += 5;
		$pdf->SetXY(15, $y);
		$pdf->Cell(10, 5, "$x", 1, 1, 'C');
		$pdf->SetXY(25, $y);
		$pdf->Cell(40, 5, "$n->nombre", 1, 1, 'C');
		$pdf->SetXY(65, $y);
		$pdf->Cell(40, 5, "$n->precio", 1, 1, 'C');
		$pdf->SetXY(105, $y);
		$pdf->Cell(50, 5, "$n->cantidad", 1, 1, 'C');
		$pdf->SetXY(155, $y);
		$pdf->Cell(40, 5, "$n->subtotal", 1, 1, 'C'); 
		$x++;
	}
	
	$pdf->Ln(3);
	$top_ventas = 175;
	$pdf->SetTextColor(0,0,0);
	$t = $_SESSION["total"];

	$pdf->SetXY(105, $top_ventas);
    $pdf->Cell(55, 5, 'Total Valor de Venta:', 1, 1, 'L');
	$pdf->SetXY(160, $top_ventas);
    $pdf->SetTextColor(100,100,100);
	$pdf->Cell(35, 5, "$t", 1, 1, 'R');
	
    $pdf->SetTextColor(0,0,0);
	$pdf->SetXY(105, $top_ventas+5);
    $pdf->Cell(55, 5, 'Sumatoria IGV:', 1, 1, 'L'); 
	$pdf->SetXY(160, $top_ventas+5);
    $pdf->SetTextColor(100,100,100);
	$pdf->Cell(35, 5, '0.00', 1, 1, 'R');	
	
	$pdf->SetTextColor(0,0,0);
	$pdf->SetXY(105, $top_ventas+10);
    $pdf->Cell(55, 5, 'Importe Total:', 1, 1, 'L'); 
	$pdf->SetXY(160, $top_ventas+10);
    $pdf->SetTextColor(100,100,100);
	$pdf->Cell(35, 5, "$t", 1, 1, 'R');
//Salto de línea
$pdf->Ln(2);

$pdf->Output('../Uploading-files-to-Google-Drive-with-PHP-master/files/'.$archivo_de_salida);//cierra el objeto pdf
echo 'Boleta Generada<br>';
echo "<a target='_blank' href='../Uploading-files-to-Google-Drive-with-PHP-master/files/$archivo_de_salida'>Ver PDF</a><br>";

unset($_SESSION["total"]);
unset($_SESSION["ruc"]);
unset($_SESSION["rzn"]);
unset($_SESSION["cab"]);
unset($_SESSION["carrito"]);
unset($_SESSION["nomArchivo"]);
 ?>
 <br>
 <a href="../index.php">Inicio</a>
	