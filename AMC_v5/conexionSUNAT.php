<?php
//header('Content-Type: text/html; charset=UTF-8');
//echo '<h2>Facturación electrónica SUNAT</h2><br>';

require dirname(__FILE__) . '/Librerias/src/xmlseclibs.php';
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

session_start();
$NomArch = $_SESSION["nomArchivo"];
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
$objKey->loadKey('certificados/clavePrivada.pem', true);
$objDSig->sign($objKey);

// Agregue la clave pública asociada a la firma
$objDSig->add509Cert(file_get_contents('certificados/clavePublica.pem'), true, false, array('subjectName' => true)); // array('issuerSerial' => true, 'subjectName' => true));
// Anexar la firma al XML
$objDSig->appendSignature($doc->getElementsByTagName('ExtensionContent')->item(1));
// Guardar el XML firmado
$doc->save('Uploading-files-to-Google-Drive-with-PHP-master/files/'.$NomArch.'.xml');
chmod('Uploading-files-to-Google-Drive-with-PHP-master/files/'.$NomArch.'.xml', 0777);

require('lib/pclzip.lib.php'); // Librería que comprime archivos en .ZIP

## Creación del archivo .ZIP

$zip = new PclZip('Uploading-files-to-Google-Drive-with-PHP-master/files/'.$NomArch.'.zip');
$zip->create('Uploading-files-to-Google-Drive-with-PHP-master/files/'.$NomArch.'.xml', PCLZIP_OPT_REMOVE_ALL_PATH);
chmod('Uploading-files-to-Google-Drive-with-PHP-master/files/'.$NomArch.'.zip', 0777);

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
        <contentFile>' . base64_encode(file_get_contents('./Uploading-files-to-Google-Drive-with-PHP-master/files/'.$NomArch.'.zip')) . '</contentFile>
     </ser:sendBill>
 </soapenv:Body>
</soapenv:Envelope>';

//Realizamos la llamada a nuestra función
$result = soapCall($wsdlURL, $callFunction = "sendBill", $XMLString);

//Descargamos el Archivo Response
$archivo = fopen('Uploading-files-to-Google-Drive-with-PHP-master/files/'.'C'.$NomArch.'.xml','w+');
fputs($archivo,$result);
fclose($archivo);


/*LEEMOS EL ARCHIVO XML*/
$xml = simplexml_load_file('Uploading-files-to-Google-Drive-with-PHP-master/files/'.'C'.$NomArch.'.xml'); 
foreach ($xml->xpath('//applicationResponse') as $response){ }

/*AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)*/
$cdr=base64_decode($response);
$archivo = fopen('Uploading-files-to-Google-Drive-with-PHP-master/files/'.'R-'.$NomArch.'.zip','w+');
fputs($archivo,$cdr);
fclose($archivo);
chmod('Uploading-files-to-Google-Drive-with-PHP-master/files/'.'R-'.$NomArch.'.zip', 0777);

$archive = new PclZip('Uploading-files-to-Google-Drive-with-PHP-master/files/'.'R-'.$NomArch.'.zip');
if ($archive->extract('Uploading-files-to-Google-Drive-with-PHP-master/files/')==0) { 
    die("Error : ".$archive->errorInfo(true)); 
}else{
    chmod('Uploading-files-to-Google-Drive-with-PHP-master/files/'.'R-'.$NomArch.'.xml', 0777);    
} 

/*echo '<div style="font-family: Arial; font-size: 12pt; color: #000000; margin-top: 10px;">';
echo 'Archivo .XML enviado a la SUNAT, nos retornó una constancia de recepción (CRD):<br>';
echo '<span style="color: red;">R-'.$NomArch.'.xml</span>';
echo '</div>';*/

/*Eliminamos el Archivo Response*/
unlink('Uploading-files-to-Google-Drive-with-PHP-master/files/'.'C'.$NomArch.'.xml');
/*echo "<br>
 <a target='_blank' href='Uploading-files-to-Google-Drive-with-PHP-master/files/R-$NomArch.xml'>Ver XML de Aceptación</a>";*/
 header("location: PDF/generarFacturaPdf.php");
 ?>
