<?php 

//Conexion para el equipo Primario
$t1 = new mysqli('localhost','root','javac','sicar');
if($t1->connect_errno):
	echo "Error de conexion".$t1->connect_error;
endif;


// Conexion para el equipo Secundario

$direccion_ip = $_POST['inputsucursal'];

switch ($direccion_ip) {
    case "1":
    $server_ip = "192.168.10.104";
    break;
    
    case "2":
    $server_ip = "192.168.10.104";
    break;
    
    case "3":
    $server_ip = "192.168.10.104";
    break;
    
    case "4":
    $server_ip = "192.168.10.104";
    break;
    
    case "5":
    $server_ip = "192.168.10.104";
    break;
    
    case "6":
    $server_ip = "192.168.10.104";
    break;
    
    case "7":
    $server_ip = "192.168.10.104";
    break;
    
    case "8":
    $server_ip = "192.168.10.104";
    break;
    
    case "9":
    $server_ip = "192.168.10.104";
    break;
    
    case "10":
    $server_ip = "192.168.10.104";
    break;
    
    case "11":
    $server_ip = "192.168.10.104";
    break;
    
    case "12":
    $server_ip = "192.168.10.104";
    break;
    
    case "13":
    $server_ip = "192.168.10.104";
    break;
    
    default:
        echo "Servidor Secundario NO Encontrado!";
}

$user_db = "root";
$password_db= "javac";
$db = "sicar";

$t2 = new mysqli($server_ip, $user_db, $password_db, $db);

if($t2->connect_errno):
	echo "Error de conexion".$t2->connect_error;
endif;


//Envio de Datos desde el Formulario.
$clave_articulo = $_POST['inputclave'];
$cantidad_articulo_ingresada = $_POST['inputcantidad'];
$comentario_sucursal = $_POST['inputsucursal'];



//Query para la Base de Datos del equipo Primario.

//Verifica si existe el Articulo.
$query_consulta_articulo = "SELECT* FROM articulo where clave='".$clave_articulo."' or claveAlterna = '".$clave_articulo."'";
$mysql_consulta_articulo = mysqli_query($t1, $query_consulta_articulo);
$resultado_consulta_articulo = mysqli_fetch_array($mysql_consulta_articulo);


//Realiza la Actualizacion de la nueva existencia.
$nueva_existencia = $resultado_consulta_articulo['existencia'] - $cantidad_articulo_ingresada;
$query_articulo_actualizarinventario = "UPDATE articulo SET existencia ='".$nueva_existencia."' WHERE clave = '".$clave_articulo."' or claveAlterna ='".$clave_articulo."'";

//Insertar datos en tabla ajusteinventario para el comentario de Transeferencia.
ini_set('date.timezone','America/Mexico_City');
$fecha_actual_ajusteinventario = $fecha = date("Y")."-".date("m")."-".date("d")." ". date("H").":".date("i").":".date("s");
$comentario_ajusteinventario = "Enviado a &rarr; Tienda ".$comentario_sucursal;
$query_ajusteinventario = "INSERT INTO ajusteinventario(fecha, comentario) values ('".$fecha_actual_ajusteinventario."','".$comentario_ajusteinventario."')";

//Insertar Datos en la tabala ajusteinventarioarticulo.
$query_consulta_ajusteinventario = "SELECT * FROM ajusteinventario ORDER BY ain_id DESC LIMIT 1";
$mysql_consulta_ajusteinventario = mysqli_query($t1, $query_consulta_ajusteinventario);
$resultado_consulta_ajusteinventario = mysqli_fetch_array($mysql_consulta_ajusteinventario);

$ain_id = $resultado_consulta_ajusteinventario['ain_id'] + 1;
$art_id = $resultado_consulta_articulo['art_id'];
$exisAnterior = $resultado_consulta_articulo['existencia'];
$exisActual = $cantidad_articulo_ingresada + $resultado_consulta_articulo['existencia'];

$query_ajusteinventarioarticulo = "INSERT INTO ajusteinventarioarticulo(ain_id, art_id, exisAnterior, exisActual) values ('".$ain_id."','".$art_id."','".$exisAnterior."','".$exisActual."')";

//Insertar Datos en Tabla Historial.
$query_historial = "INSERT INTO historial(movimiento, fecha, tabla, id, usu_id) VALUES ('0', '".$fecha_actual_ajusteinventario."', 'AjusteInventario', '".$ain_id."', '1')";




//Query para la Base de Datos del equipo Secundario.

//Verifica si existe el Articulo.
$s_query_consulta_articulo = "SELECT* FROM articulo where clave='".$clave_articulo."' or claveAlterna = '".$clave_articulo."'";
$s_mysql_consulta_articulo = mysqli_query($t2, $s_query_consulta_articulo);
$s_resultado_consulta_articulo = mysqli_fetch_array($s_mysql_consulta_articulo);


//Realiza la Actualizacion de la nueva existencia.
$s_nueva_existencia = $s_resultado_consulta_articulo['existencia'] + $cantidad_articulo_ingresada;
$s_query_articulo_actualizarinventario = "UPDATE articulo SET existencia ='".$s_nueva_existencia."' WHERE clave = '".$clave_articulo."' or claveAlterna ='".$clave_articulo."'";

//Insertar datos en tabla ajusteinventario para el comentario de Transeferencia.
$s_fecha_actual_ajusteinventario = $fecha = date("Y")."-".date("m")."-".date("d")." ". date("H").":".date("i").":".date("s");
$s_comentario_ajusteinventario = "Entregado &rarr; Tienda 1";
$s_query_ajusteinventario = "INSERT INTO ajusteinventario(fecha, comentario) values ('".$fecha_actual_ajusteinventario."','".$s_comentario_ajusteinventario."')";

//Insertar Datos en la tabala ajusteinventarioarticulo.
$s_query_consulta_ajusteinventario = "SELECT * FROM ajusteinventario ORDER BY ain_id DESC LIMIT 1";
$s_mysql_consulta_ajusteinventario = mysqli_query($t2, $s_query_consulta_ajusteinventario);
$s_resultado_consulta_ajusteinventario = mysqli_fetch_array($s_mysql_consulta_ajusteinventario);

$s_ain_id = $s_resultado_consulta_ajusteinventario['ain_id'] + 1;
$s_art_id = $s_resultado_consulta_articulo['art_id'];
$s_exisAnterior = $s_resultado_consulta_articulo['existencia'];
$s_exisActual = $cantidad_articulo_ingresada + $s_resultado_consulta_articulo['existencia'];

$s_query_ajusteinventarioarticulo = "INSERT INTO ajusteinventarioarticulo(ain_id, art_id, exisAnterior, exisActual) values ('".$s_ain_id."','".$s_art_id."','".$s_exisAnterior."','".$s_exisActual."')";

//Insertar Datos en Tabla Historial.
$s_query_historial = "INSERT INTO historial(movimiento, fecha, tabla, id, usu_id) VALUES ('0', '".$s_fecha_actual_ajusteinventario."', 'AjusteInventario', '".$s_ain_id."', '1')";



if($mysql_consulta_articulo-> num_rows == 1 && $s_mysql_consulta_articulo-> num_rows == 1){
	if($nueva_existencia>=0 && mysqli_query($t1, $query_articulo_actualizarinventario) && $s_nueva_existencia>=0 && mysqli_query($t2, $s_query_articulo_actualizarinventario)){
		if(mysqli_query($t1, $query_ajusteinventario) && mysqli_query($t2, $s_query_ajusteinventario)) {
			if(mysqli_query($t1, $query_ajusteinventarioarticulo) && mysqli_query($t2, $s_query_ajusteinventarioarticulo)) {
				if (mysqli_query($t1, $query_historial) && mysqli_query($t2, $s_query_historial)) {
					echo 1;
				}
			}
		}
	}
}else{
	echo 0;
}

//INSERT INTO ajusteinventario(fecha, comentario) values('2018-10-23 10:00:00', 'hola');

//insert into ajusteinventarioarticulo(ain_id, art_id, exisAnterior, exisActual) values ('9','2','4','10');

//insert into historial(movimiento, fecha, tabla, id, usu_id) values ('0','2018-10-24 10:12:00','AjusteInventario','40','1');
?>

