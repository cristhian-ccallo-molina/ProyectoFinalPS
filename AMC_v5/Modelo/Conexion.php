<?php

class Conexion{

 	public $con;

 	public function __construct (){

 		/*$this->con = new mysqli(server,user, pass, BDname);*/
   		//$this->con = new mysqli('localhost','root','','BD_AMC_V2');
   		$this->con = mysqli_connect("localhost", "root", "admin", "1334893db2");
   		mysqli_select_db($this->con, "1334893db2");

    	if (!$this->con) die ("No se ha podido establecer la conexión. " .mysql_error());
   	}
    public function ejecutar($query){
      return  mysqli_query($this->con,$query); 
    }
  }

?>