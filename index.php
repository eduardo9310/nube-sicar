<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title>Nube SICAR</title>
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
				<h3 class="font-weight-bold"> Tienda 2</h3>
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
					<i class="far fa-spinner fa-spin" style="font-size:80px; color:#339af0"></i>
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
						echo "Error de conexion" . $t1->connect_error;
					endif;

					$query_articulo = "SELECT a.clave, a.descripcion, a.precio1, aia.exisAnterior, aia.exisActual, ai.comentario FROM ajusteinventarioarticulo aia INNER JOIN articulo a ON aia.art_id = a.art_id INNER JOIN ajusteinventario ai ON aia.ain_id = ai.ain_id ORDER BY ai.fecha DESC LIMIT 10";

					$consulta_articulo = mysqli_query($t1, $query_articulo);

					while($row = mysqli_fetch_array($consulta_articulo)){

						$cadena_entrada = $row['comentario'];
						$buscar_entrada = "Entrada";
						$posicion_entrada = strpos($cadena_entrada, $buscar_entrada);

						$cadena_salida = $row['comentario'];
						$buscar_salida = 'Salida';
						$posicion_salida = strpos($cadena_salida, $buscar_salida);

						if($posicion_entrada === 0 or $posicion_salida === 0) {

							if ($posicion_entrada === 0) {

								echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
								<strong>';
								echo $row['exisActual']- $row['exisAnterior'];
								echo'&nbsp;&nbsp;&nbsp;</strong>';
								echo $row['clave'] . "&nbsp;" . $row['descripcion'] . "&nbsp;&nbsp;&nbsp;&nbsp;" . $row['comentario'] . "<br>";
								echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
								</div>';
							}

							else{

								echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<strong>';
								echo $row['exisActual'] - $row['exisAnterior'];
								echo'&nbsp;&nbsp;&nbsp;</strong>';
								echo $row['clave'] . "&nbsp;" . $row['descripcion'] . "&nbsp;&nbsp;&nbsp;&nbsp;" . $row['comentario'] . "<br>";
								echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
								</div>';
							}

						}
					}
					?>
					
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