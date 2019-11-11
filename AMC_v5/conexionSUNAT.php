<?php
header('Content-Type: text/html; charset=UTF-8');
echo '<h2>Facturación electrónica SUNAT</h2><br>';

require dirname(__FILE__) . '/robrichards/src/xmlseclibs.php';
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

session_start();
$NomArch = $_SESSION["nomArchivo"];
unset($_SESSION["nomArchivo"]);

// Cargar el XML a firmar
$doc = new DOMDocument();
$doc->load('Facturas sin firmar/'.$NomArch.'.xml');

// Crear un nuevo objeto de seguridad
$objDSig = new XMLSecurityDSig();

// Utilizar la canonización exclusiva de c14n
$objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);

// Firmar con SHA-256
$objDSig->addReference(
    $doc,
    XMLSecurityDSig::SHA1,
    array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'),
    array('force_uri' => true)
);

//Crear una nueva clave de seguridad (privada)
$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));

//Cargamos la clave privada
$objKey->loadKey('archivos_pem/private_key.pem', true);
$objDSig->sign($objKey);

// Agregue la clave pública asociada a la firma
$objDSig->add509Cert(file_get_contents('archivos_pem/public_key.pem'), true, false, array('subjectName' => true)); // array('issuerSerial' => true, 'subjectName' => true));
// Anexar la firma al XML
$objDSig->appendSignature($doc->getElementsByTagName('ExtensionContent')->item(1));
// Guardar el XML firmado
$doc->save('Facturas firmadas/'.$NomArch.'.xml');
chmod('Facturas firmadas/'.$NomArch.'.xml', 0777);

require('lib/pclzip.lib.php'); // Librería que comprime archivos en .ZIP

## Creación del archivo .ZIP

$zip = new PclZip('Facturas firmadas/'.$NomArch.'.zip');
$zip->create('Facturas firmadas/'.$NomArch.'.xml', PCLZIP_OPT_REMOVE_ALL_PATH);
chmod('Facturas firmadas/'.$NomArch.'.zip', 0777);

# Procedimiento para enviar comprobante a la SUNAT
class feedSoap extends SoapClient{
    public $XMLStr = "";
    public function setXMLStr($value){
        $this->XMLStr = $value;
    }
    public function getXMLStr(){
        return $this->XMLStr;
    }
    public function __doRequest($request, $location, $action, $version, $one_way = 0){
        $request = $this->XMLStr;
        $dom = new DOMDocument('1.0');
        try{
            $dom->loadXML($request);
        } catch (DOMException $e) {
            die($e->code);
        }
        $request = $dom->saveXML();
        //Solicitud
        return parent::__doRequest($request, $location, $action, $version, $one_way = 0);
    }
    public function SoapClientCall($SOAPXML){
        return $this->setXMLStr($SOAPXML);
    }
}

function soapCall($wsdlURL, $callFunction = "", $XMLString)
{
    $client = new feedSoap($wsdlURL, array('trace' => true));
    $reply  = $client->SoapClientCall($XMLString);

    //echo "REQUEST:\n" . $client->__getFunctions() . "\n";
    $client->__call("$callFunction", array(), array());
    //$request = prettyXml($client->__getLastRequest());
    //echo highlight_string($request, true) . "<br/>\n";
    return $client->__getLastResponse();
}

//URL para enviar las solicitudes a SUNAT
$wsdlURL = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';

//Estructura del XML para la conexión
$XMLString = '<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
 <soapenv:Header>
     <wsse:Security>
         <wsse:UsernameToken Id="ABC-123">
             <wsse:Username>20532710066MODDATOS</wsse:Username>
             <wsse:Password>MODDATOS</wsse:Password>
         </wsse:UsernameToken>
     </wsse:Security>
 </soapenv:Header>
 <soapenv:Body>
     <ser:sendBill>
        <fileName>'.$NomArch.'.zip</fileName>
        <contentFile>' . base64_encode(file_get_contents('./Facturas firmadas/'.$NomArch.'.zip')) . '</contentFile>
     </ser:sendBill>
 </soapenv:Body>
</soapenv:Envelope>';

//Realizamos la llamada a nuestra función
$result = soapCall($wsdlURL, $callFunction = "sendBill", $XMLString);

//Descargamos el Archivo Response
$archivo = fopen('Facturas firmadas/'.'C'.$NomArch.'.xml','w+');
fputs($archivo,$result);
fclose($archivo);


/*LEEMOS EL ARCHIVO XML*/
$xml = simplexml_load_file('Facturas firmadas/'.'C'.$NomArch.'.xml'); 
foreach ($xml->xpath('//applicationResponse') as $response){ }

/*AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)*/
$cdr=base64_decode($response);
$archivo = fopen('Facturas firmadas/'.'R-'.$NomArch.'.zip','w+');
fputs($archivo,$cdr);
fclose($archivo);
chmod('Facturas firmadas/'.'R-'.$NomArch.'.zip', 0777);

$archive = new PclZip('Facturas firmadas/'.'R-'.$NomArch.'.zip');
if ($archive->extract('Facturas firmadas/')==0) { 
    die("Error : ".$archive->errorInfo(true)); 
}else{
    chmod('Facturas firmadas/'.'R-'.$NomArch.'.xml', 0777);    
} 

echo '<div style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12pt; color: #000000; margin-top: 10px;">';
echo 'Archivo .XML enviado a la SUNAT, nos retornó una constancia de recepción (CRD):<br>';
echo '<span style="color: red;">R-'.$NomArch.'.xml</span>';
echo '</div>';

/*Eliminamos el Archivo Response*/
unlink('Facturas firmadas/'.'C'.$NomArch.'.xml');
echo "<br>
 <a target='_blank' href='Facturas firmadas/R-$NomArch.xml'>Ver XML de Aceptación</a>";
 ?>
 <br>
 <a href="index.php">Inicio</a>
