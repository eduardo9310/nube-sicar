<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title>Traspasos SICAR</title>
	<link rel="stylesheet" href="css/index.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/alertify.css">
	<script src="js/jquery-3.2.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/alertify.js"></script>
	<script src="js/fontawesome.min.js"></script>
</head>

<body class="bg">

	<div class="container">
		<div class="row mt-1">
			<div class="col-10 css-title">
				<h1 class="text-center text-primary"> Nube SICAR </h1>
			</div>
			<div class="col-2 css-title css-title-tienda">
				<h3 class="font-weight-bold"> Tienda 3</h3>
			</div>
		</div>

		<div class="row mt-3">
			<div class="col-4 css-opciones">
				<div class="h3-titile">
					<h3 class="text-center">Enviar Artículos</h3>
				</div>

				<form autocomplete="off" method="POST" id="form-datos">
					<select class="form-control form-control-lg text-center text-warning font-weight-bold" name="inputsucursal" id="inputsucursal">
						<option value="0" class="text-danger">Seleccione la Sucursal</option>
						<option value="1"> Tienda 1 </option>
						<option value="2"> Tienda 2 </option>
						<option value="3"> Tienda 3 </option>
						<option value="4"> Tienda 4 </option>
						<option value="5"> Tienda 5 </option>
						<option value="6"> Tienda 6 </option>
						<option value="7"> Tienda 7 </option>
						<option value="8"> Tienda 8 </option>
						<option value="9"> Tienda 9 </option>
						<option value="10"> Tienda 10 </option>
					</select>

					<div class="form-group mt-3">
						<label for="exampleInputEmail1" class="text-info">Cantidad</label>
						<input type="number" class="form-control text-center" id="inputcantidad" name="inputcantidad" placeholder="Ejemplo: 1">
					</div>

					<div class="form-group">
						<label for="exampleInputEmail1" class="text-info">Clave del Articulo</label>
						<input type="text" class="form-control text-center" id="inputclave" name="inputclave" placeholder="Ejemplo: N0021">
						<small id="emailHelp" class="form-text text-center text-danger">Escanea la clave del Artículo</small>
					</div>

					<button class="btn btn-block btn-primary" id="btn-enviar">Enviar Datos</button>
				</form>

				<div class="mt-5 text-center" id="notificacion">
					<i class="far fa-spinner fa-spin" style="font-size:80px;color:#339af0"></i>
				</div>
			</div>

			<div class="col-8 css-listamovimientos">
				<div class="h3-titile">
					<h3 class="text-primary text-center flex-column"> Movimientos</h3>
				</div>

				<div class="lista-movimientos" id="refresh-data">
					<?php 

					$t1 = new mysqli('localhost','root','javac','sicar');
					
					if($t1->connect_errno):
						echo "Error de conexion".$t1->connect_error;
					endif;

					$query_consulta_ajusteinventarioarticulo = "SELECT * FROM ajusteinventarioarticulo order by ain_id desc limit 7";
					$mysqli_consulta_ajusteinventarioarticulo = mysqli_query($t1, $query_consulta_ajusteinventarioarticulo);



					while($row = mysqli_fetch_array($mysqli_consulta_ajusteinventarioarticulo)) {

						$query_consulta_articulo = "SELECT * FROM articulo where art_id='".$row['art_id'] ."'";
						$mysqli_consulta_articulo = mysqli_query($t1, $query_consulta_articulo);

						$query_consulta_ajusteinventario = "SELECT * FROM ajusteinventario where ain_id ='".$row['ain_id']."'";
						$mysqli_consulta_ajusteinventario = mysqli_query($t1, $query_consulta_ajusteinventario);

						echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
						echo $row['exisActual'] -  $row['exisAnterior'];
						echo "pzs</span> &nbsp;";

						while($row_articulo = mysqli_fetch_array($mysqli_consulta_articulo)) {

							echo  "<span class='text-uppercase'>".$row_articulo['clave']."</span> &nbsp;".$row_articulo['descripcion'];

							while($row_ajusteinventario = mysqli_fetch_array($mysqli_consulta_ajusteinventario)) {

								$cadena_de_texto = $row_ajusteinventario['comentario'];
								$cadena_buscada   = 'Entregado';
								$posicion_coincidencia = strpos($cadena_de_texto, $cadena_buscada);


								if($posicion_coincidencia === false) {
									echo "&nbsp; <strong class='text-danger'> &nbsp; &nbsp;" . $row_ajusteinventario['comentario']."</strong><br>";
									echo "<button type='button' class='close' data-dismiss='alert' aria-label='Close' id='btn-close'>
									<span aria-hidden='true'>&times;</span></button></div>";									

								}else{
									echo "&nbsp; <strong class='text-success'> &nbsp; &nbsp;" . $row_ajusteinventario['comentario']."</strong> <br>";
									echo "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
									<span aria-hidden='true'>&times;</span></button></div>";
								}

							}
						}

					}
					?>
				</div>
			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
	
	var time = setInterval(function(){
		$('#refresh-data').load(' #refresh-data');
	}, 8500);


	notification_hide();

	function notification_hide(){

		$('#notificacion').hide();
	}

	function notification_show(){
		$('#notificacion').show();
	}


	function notification_hite_incorrecto(e){
		setTimeout(function(){
			alertify.warning("Datos Incorrectos");
			notification_hide();
		},1000);
	}

	function notification_hite_errorTransferencia(){
		alertify.error("Error en la Transferencia");
		notification_hide();
	}


	function refresh(){
		$('#refresh-data').load(' #refresh-data');
	}

	$(document).ready(function() {

		$('#btn-enviar').click(function(e){
			e.preventDefault();

			refresh();
			notification_show();

			var datos = $('#form-datos').serialize();
			var sucursal_a = $('#inputsucursal').val();
			var cantidad_a = $('#inputcantidad').val();
			var clave_a = $('#inputclave').val();

			if(sucursal_a !="0"  && cantidad_a > 0 && cantidad_a % 1 == 0 && clave_a !=''){
				$.ajax({
					type:"POST",
					url: "crud/crud.php",
					data: datos,

					success:function(e){
						if(e == 1){

							alertify.success("Transferencia Exitosa");
							$('#inputsucursal').val('0');
							$('#inputcantidad').val('');
							$('#inputclave').val('');
							notification_hide();

							refresh();

						}else{
							notification_show();
							notification_hite_errorTransferencia();
						}
					}
				});
			}else{
				notification_show();
				notification_hite_incorrecto();
			}
		});
	});
</script>

</body>
</html>