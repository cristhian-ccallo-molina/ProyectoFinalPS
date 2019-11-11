<?php 
	require_once "Conexion.php";
	require_once "Detalle.php";
	require_once "boletaCabecera.php";
	require_once "boletaDetalle.php";
	require_once "tipoOperacion.php";
	require_once "localEmisor.php";
	require_once "nombreCliente.php";

	class Data {
		private $con;


		public function __construct(){
			$this->con = new Conexion();
		}
		public function getProductos(){
			$productos = array();
			$query ="SELECT * FROM PRODUCTO";
			$res = $this->con->ejecutar($query);

			while ($reg = mysqli_fetch_array($res)) {
				$p = new Detalle();

				$p->id=$reg[0];
				$p->nombre=$reg[3];
				$p->precio=$reg[4];
				$p->stock=$reg[5];

				array_push($productos, $p);
			}
			return $productos;
		}
		public function getBoletasCabeceras(){
			$cabeceras = array();
			$query ="SELECT * FROM BOLETA_CABECERA";
			$res = $this->con->ejecutar($query);

			while ($reg = mysqli_fetch_array($res)) {
				$b = new boletaCabecera();

				$b->id=$reg[0];
				$b->tipOperacion=$reg[1];
				$b->numBoleta=$reg[2];
				$b->fecha=$reg[3];
				$b->localEmisor=$reg[4];
				$b->tipDocUsuario=$reg[5];
				$b->docUsuario=$reg[6];
				$b->tipMoneda=$reg[7];
				$b->total=$reg[8];
				$b->sumIGV=$reg[9];
				$b->impTotal=$reg[10];

				array_push($cabeceras, $b);
			}
			return $cabeceras;
		}
		public function getUltimaCabecera(){
			$c = new boletaCabecera();

			$query = "SELECT MAX(id_boleta) FROM BOLETA_CABECERA";
			$res = $this->con->ejecutar($query);
			$re = mysqli_fetch_array($res);

			$query ="SELECT * FROM BOLETA_CABECERA WHERE id_boleta=$re[0]";
			$res2 = $this->con->ejecutar($query);

			if ($reg = mysqli_fetch_array($res2)){
				$c->id=$reg[0];
				$c->tipOperacion=$reg[1];
				$c->numBoleta=$reg[2];
				$c->fecha=$reg[3];
				$c->localEmisor=$reg[4];
				$c->tipDocUsuario=$reg[5];
				$c->docUsuario=$reg[6];
				$c->tipMoneda=$reg[7];
				$c->total=$reg[8];
				$c->sumIGV=$reg[9];
				$c->impTotal=$reg[10];
			}
			return $c;
		}
		public function getDetalles ($idBoleta) {
			$query = "SELECT d.id_detalle, p.des_item, d.ctd_unidad_item, d.mto_precio_venta_item, d.mto_valor_venta_item, p.mto_valor_unitario, p.mto_igv_item
			FROM BOLETA_DETALLE d, PRODUCTO p WHERE d.cod_producto=p.cod_producto AND d.id_boleta = $idBoleta";

			$detalles = array();

			$res = $this->con->ejecutar($query);
			while ($reg = mysqli_fetch_array($res)) {
				$det = new boletaDetalle ();
				$det->id=$reg[0];
				$det->nomProducto=$reg[1];
				$det->cantidad=$reg[2];
				$det->precio=$reg[5]; // precio unitario del producto
				$det->subtotal=$reg[3]; // total sin igv
				$det->mtoIGV=$reg[6]; //monto igv
				$det->totalDetalle=$reg[4]; // total con igv: subtotal + mtoIGV

				array_push($detalles, $det);
			}
			return $detalles;
		}

		public function tipOperaciones () {
			$oper = array();
			$query ="SELECT t.cod_tip_operacion, t.des_tip_operacion FROM TIPO_OPERACION t";
			$res = $this->con->ejecutar($query);

			while ($reg = mysqli_fetch_array($res)) {
				$p = new tipoOperacion();
				$p->id = $reg[0];
				$p->des = $reg[1];
				array_push($oper, $p);
			}
			return $oper;
		}

		public function localEmisores () {
			$local = array();
			$query ="SELECT l.cod_local_emisor, l.des_local_emisor FROM LOCAL_ANEXO_EMISOR l";
			$res = $this->con->ejecutar($query);

			while ($reg = mysqli_fetch_array($res)) {
				$p = new localEmisor();
				$p->id = $reg[0];
				$p->des = $reg[1];
				array_push($local, $p);
			}
			return $local;
		}

		public function nombreClientes () {
			$cliente = array();
			$query ="SELECT c.rzn_social_usuario FROM CLIENTE c";
			$res = $this->con->ejecutar($query);

			while ($reg = mysqli_fetch_array($res)) {
				$p = new nombreCliente();
				$p->rzn = $reg[0];
				array_push($cliente, $p);
			}
			return $cliente;
		}



		public function crearBoleta($listaDetalles, $total, $cab) {

			//crear la venta
			$c = new boletaCabecera();
			$c = $cab;
			$query = "INSERT INTO BOLETA_CABECERA values (null, '$c->tipOperacion', '$c->numBoleta', '$c->fecha', '$c->localEmisor', '$c->tipDocUsuario', '$c->docUsuario', '$c->tipMoneda', '$total','0', '$total')";
			$this->con->ejecutar($query);

			//rescatar la ultima venta (id)
			$query = "SELECT MAX(id_boleta) FROM BOLETA_CABECERA";
			$res = $this->con->ejecutar($query);

			$idUltimaCabesera=0;
			if ($reg = mysqli_fetch_array($res)){
					$idUltimaCabesera=$reg[0];
			}
			 
			//los insert en el detalle
			foreach ($listaDetalles as $p) {
				$query = "INSERT INTO BOLETA_DETALLE values (null,$idUltimaCabesera, $p->id, $p->cantidad, '0', $p->precio, $p->subtotal)";

				$this->con->ejecutar($query);
				$this->actualizarStock($p->id, $p->cantidad);
			}
			
		//descontar el stock
		}
		public function actualizarStock ($id, $stockADescontar) {
			$query = "SELECT stock FROM PRODUCTO where cod_producto=$id";
			$res = $this->con->ejecutar($query);

			$stockActual = 0;
			if ($reg = mysqli_fetch_array($res)){
					$stockActual=$reg[0];
			}

			$stockActual -= $stockADescontar;

			$query = "UPDATE PRODUCTO set stock=$stockActual where cod_producto=$id";
			$this->con->ejecutar($query);
		}
	}
 ?>