<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 
<?php 
	require_once "Modelo/Data.php";

	$data = new Data();
	$ca = $data->getUltimaCabecera();
	$de = $data->getDetalles($ca->id);

	session_start();
	$numRuc = $_SESSION["ruc"];

	$tipo_documento = str_pad($ca->tipOperacion, 2, "0", STR_PAD_LEFT); 
	$numeracion = $ca->numBoleta;
	$fecha_emi = $ca->fecha;
	$nombre_dir_emi = $ca->localEmisor;
	$tip_doc_us = $ca->tipDocUsuario;
	$num_doc_us = $ca->docUsuario;
	$ap_nomb_den_razSoc = "hola SP";
	$num_orden = "1";
	$uni_med = "kg";
	$cantidad = $de[0]->cantidad;
	$codigo_prod = $de[0]->nomProducto;
	$descripcion = "feo";
	$prec_uni = $de[0]->precio;
	$afect_igv_item = "10";
	$igv_item = "0";
	$total_ventas = $_SESSION["total"];
	$sum_igv = "0";
	$imp_tot_venta = $_SESSION["total"];
		
    /*
     * 1.- Creamos la variable que contiene el archivo que tenemos que crear.
     * 2.- preguntamos si existe el archivo, si el archivo existe "se ha modificado"
       en caso contrario el archivo se ha creado.
     * 3.- Con fopen abrimos un archivo o url, en este caso vamos a abrir un archivo
       pasando como parámetro la variable $nombre_archivo que es la que contiene 
       nuestro archivo y como segundo parámetro como lo vamos a abrir, en este caso "a"
       que nos abre el fichero en solo lectura y sitúa el puntero al final del fichero
       y en el caso de que no exista lo crea.
 
       ******Para terminar*******
 
       4.-Con el fwrite escribimos dentro del archivo la fecha con la hora de Creación 
       o modificación, según el caso, con la variable $mensaje, 
 
    */
	
	//Nombre del archivo XML.
	
    //$nombre_archivoXML = $numRuc."-".$tipo_documento."-F002-0000000".$ca->id;
    $nBol = str_pad($ca->id, 8, "0", STR_PAD_LEFT);
    $nombre_archivoXML = $numRuc."-".$tipo_documento."-F002-".$nBol;
	
	if(isset($_SESSION["nomArchivo"])){
		$nArchivo = $_SESSION["nomArchivo"];
	} else {
		$nArchivo = $nombre_archivoXML;
	}
	$_SESSION["nomArchivo"] = $nArchivo;


	//Contenido del XML.
	$mensaje = "<?xml version='1.0' encoding='ISO-8859-1' standalone='no'?>
<Invoice xmlns='urn:oasis:names:specification:ubl:schema:xsd:Invoice-2' xmlns:cac='urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2' xmlns:cbc='urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2' xmlns:ccts='urn:un:unece:uncefact:documentation:2' xmlns:ds='http://www.w3.org/2000/09/xmldsig#' xmlns:ext='urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2' xmlns:qdt='urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2' xmlns:sac='urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1' xmlns:udt='urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2'>
  <ext:UBLExtensions>
    <ext:UBLExtension>
      <ext:ExtensionContent>
        <sac:AdditionalInformation>
          <sac:AdditionalMonetaryTotal>
            <cbc:ID>2005</cbc:ID>
            <cbc:PayableAmount currencyID='PEN'>$total_ventas</cbc:PayableAmount>
          </sac:AdditionalMonetaryTotal>
          <sac:AdditionalMonetaryTotal>
            <cbc:ID>1001</cbc:ID>
            <cbc:PayableAmount currencyID='PEN'>$total_ventas</cbc:PayableAmount>
          </sac:AdditionalMonetaryTotal>
          <sac:AdditionalMonetaryTotal>
            <cbc:ID>1002</cbc:ID>
            <cbc:PayableAmount currencyID='PEN'>0.00</cbc:PayableAmount>
          </sac:AdditionalMonetaryTotal>
          <sac:AdditionalMonetaryTotal>
            <cbc:ID>1003</cbc:ID>
            <cbc:PayableAmount currencyID='PEN'>0.00</cbc:PayableAmount>
          </sac:AdditionalMonetaryTotal>

          <sac:AdditionalProperty>
            <cbc:ID>1000</cbc:ID>
            <cbc:Value>1000</cbc:Value>
          </sac:AdditionalProperty>
          <sac:AdditionalProperty>
            <cbc:ID>1002</cbc:ID>
            <cbc:Value>1002 AAAAA</cbc:Value>
          </sac:AdditionalProperty>
          <sac:AdditionalProperty>
            <cbc:ID>2000</cbc:ID>
            <cbc:Value>2000 BBBB</cbc:Value>
          </sac:AdditionalProperty>
          <sac:AdditionalProperty>
            <cbc:ID>2002</cbc:ID>
            <cbc:Value>2002 CCC</cbc:Value>
          </sac:AdditionalProperty>
          <sac:AdditionalProperty>
            <cbc:ID>2003</cbc:ID>
            <cbc:Value>2003 DDD</cbc:Value>
          </sac:AdditionalProperty>
          <sac:AdditionalProperty>
            <cbc:ID>2005</cbc:ID>
            <cbc:Value>2005 EEE</cbc:Value>
          </sac:AdditionalProperty>
          <sac:AdditionalProperty>
            <cbc:ID>2006</cbc:ID>
            <cbc:Value>2006 AAAAA</cbc:Value>
          </sac:AdditionalProperty>
          <sac:AdditionalProperty>
            <cbc:ID>2007</cbc:ID>
            <cbc:Value>2007 GGG</cbc:Value>
          </sac:AdditionalProperty>
          <sac:AdditionalProperty>
            <cbc:ID>3000</cbc:ID>
            <cbc:Value>3000 FOB</cbc:Value>
          </sac:AdditionalProperty>

          <sac:SUNATTransaction>
            <cbc:ID>1</cbc:ID>
          </sac:SUNATTransaction>
        </sac:AdditionalInformation>
      </ext:ExtensionContent>
    </ext:UBLExtension>
    <ext:UBLExtension>
      <ext:ExtensionContent> </ext:ExtensionContent>
    </ext:UBLExtension>
  </ext:UBLExtensions>
  <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
  <cbc:CustomizationID>1.0</cbc:CustomizationID>
  <cbc:ID>F002-$nBol</cbc:ID>
  <cbc:IssueDate>$fecha_emi</cbc:IssueDate>

  <cbc:InvoiceTypeCode>$tipo_documento</cbc:InvoiceTypeCode>

  <cbc:DocumentCurrencyCode>PEN</cbc:DocumentCurrencyCode>
  <cac:Signature>
    <cbc:ID>$numRuc</cbc:ID>
    <cbc:Note>Elaborado por Sistema de Emision Electronica Facturador SUNAT (SEE-SFS) 1.0.5</cbc:Note>
    <cbc:ValidatorID>780086</cbc:ValidatorID>
    <cac:SignatoryParty>
      <cac:PartyIdentification>
        <cbc:ID>$numRuc</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name>Prueba AMC S.A.</cbc:Name>
      </cac:PartyName>
      <cac:AgentParty>
        <cac:PartyIdentification>
          <cbc:ID>$numRuc</cbc:ID>
        </cac:PartyIdentification>
        <cac:PartyName>
          <cbc:Name>Prueba AMC S.A.</cbc:Name>
        </cac:PartyName>
        <cac:PartyLegalEntity>

          <cbc:RegistrationName>Grupo AMC </cbc:RegistrationName>

        </cac:PartyLegalEntity>
      </cac:AgentParty>
    </cac:SignatoryParty>
    <cac:DigitalSignatureAttachment>
      <cac:ExternalReference>
        <cbc:URI>SIGN</cbc:URI>
      </cac:ExternalReference>
    </cac:DigitalSignatureAttachment>
  </cac:Signature>
  <cac:AccountingSupplierParty>

    <cbc:CustomerAssignedAccountID>$numRuc</cbc:CustomerAssignedAccountID>
    <cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
    <cac:Party>
      <cac:PartyName>
        <cbc:Name>Prueba AMC S.A.</cbc:Name>
      </cac:PartyName>
      <cac:PostalAddress>
        <cbc:ID>040101</cbc:ID>
        <cbc:StreetName>Grupo AMC ISS</cbc:StreetName>
        <cac:Country>
          <cbc:IdentificationCode>PER</cbc:IdentificationCode>
        </cac:Country>
      </cac:PostalAddress>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName>Grupo AMC</cbc:RegistrationName>
      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:AccountingSupplierParty>
  <cac:AccountingCustomerParty>
    <cbc:CustomerAssignedAccountID>$numRuc</cbc:CustomerAssignedAccountID>
    <cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
    <cac:Party>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName>VIP</cbc:RegistrationName>

      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:AccountingCustomerParty>
  <cac:SellerSupplierParty>
    <cac:Party>
      <cac:PostalAddress>
        <cbc:AddressTypeCode>0</cbc:AddressTypeCode>
      </cac:PostalAddress>
    </cac:Party>
  </cac:SellerSupplierParty>
  <cac:TaxTotal>
    <cbc:TaxAmount currencyID='PEN'>18.00</cbc:TaxAmount>
    <cac:TaxSubtotal>
      <cbc:TaxAmount currencyID='PEN'>18.00</cbc:TaxAmount>
      <cac:TaxCategory>
        <cac:TaxScheme>
          <cbc:ID>1000</cbc:ID>
          <cbc:Name>IGV</cbc:Name>
          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
        </cac:TaxScheme>
      </cac:TaxCategory>
    </cac:TaxSubtotal>
  </cac:TaxTotal>
  <cac:LegalMonetaryTotal>
    <cbc:AllowanceTotalAmount currencyID='PEN'>0.00</cbc:AllowanceTotalAmount>
    <cbc:ChargeTotalAmount currencyID='PEN'>0.00</cbc:ChargeTotalAmount>
    <cbc:PayableAmount currencyID='PEN'>$imp_tot_venta</cbc:PayableAmount>
  </cac:LegalMonetaryTotal>
  <cac:InvoiceLine>
    <cbc:ID>1</cbc:ID>
    <cbc:InvoicedQuantity unitCode='ZZ'>$cantidad</cbc:InvoicedQuantity>
    <cbc:LineExtensionAmount currencyID='PEN'>100.00</cbc:LineExtensionAmount>
    <cac:PricingReference>
      <cac:AlternativeConditionPrice>
        <cbc:PriceAmount currencyID='PEN'>$prec_uni</cbc:PriceAmount>
        <cbc:PriceTypeCode>01</cbc:PriceTypeCode>
      </cac:AlternativeConditionPrice>
    </cac:PricingReference>
    <cac:AllowanceCharge>
      <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
      <cbc:Amount currencyID='PEN'>0.00</cbc:Amount>
    </cac:AllowanceCharge>
    <cac:TaxTotal>
      <cbc:TaxAmount currencyID='PEN'>18.00</cbc:TaxAmount>
      <cac:TaxSubtotal>
        <cbc:TaxableAmount currencyID='PEN'>18.00</cbc:TaxableAmount>
        <cbc:TaxAmount currencyID='PEN'>18.00</cbc:TaxAmount>
        <cac:TaxCategory>
          <cbc:TaxExemptionReasonCode>10</cbc:TaxExemptionReasonCode>
          <cac:TaxScheme>
            <cbc:ID>1000</cbc:ID>
            <cbc:Name>IGV</cbc:Name>
            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
          </cac:TaxScheme>
        </cac:TaxCategory>
      </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:Item>
      <cbc:Description>$descripcion</cbc:Description>
      <cac:SellersItemIdentification>
        <cbc:ID>ALM</cbc:ID>
      </cac:SellersItemIdentification>
      <cac:AdditionalItemIdentification>
        <cbc:ID>A</cbc:ID>
      </cac:AdditionalItemIdentification>
    </cac:Item>
    <cac:Price>
      <cbc:PriceAmount currencyID='PEN'>1.00</cbc:PriceAmount>
    </cac:Price>
  </cac:InvoiceLine>
</Invoice>";
    if($archivo = fopen('Facturas sin firmar/'.$nombre_archivoXML.".xml", "a"))
    {
        if(fwrite($archivo, $mensaje))
        {
            echo "Archivo XML generado correctamente.";
        }
        else
        {
            echo "Ha habido un problema al crear el archivo";
        }
 
        fclose($archivo);
    }
	 unset($_SESSION["total"]);
	 unset($_SESSION["ruc"]);
 ?>
 <br>
 <br>
 <a href="conexionSUNAT.php">Continuar</a>
