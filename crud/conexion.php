<?php
sleep(0);
$mysqli = new mysqli('192.168.0.12','root','javac','sicar');
if ($mysqli->connect_errno):
  echo "Error de conexion".$mysqli->connect_error;
endif;


$sql = "SELECT clave, descripcion, existencia FROM articulo limit 10";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo utf8_encode("id: " . $row["clave"]. " - Descripcion: " . $row["descripcion"]. " Existencia" . $row["existencia"]. "<br>");
    }
} else {
    echo "0 results";
}
$mysqli->close();
echo "<br><br>";


sleep(0);
$T1 = new mysqli('192.168.01.84','root','javac','sicar');
if ($T1->connect_errno):
  echo "Error de conexion".$T1->connect_error;
endif;


$sql = "SELECT clave, descripcion, existencia FROM articulo limit 10";
$result = $T1->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo utf8_encode("id: " . $row["clave"]. " - Descripcion: " . $row["descripcion"]. " Existencia" . $row["existencia"]. "<br>");
    }
} else {
    echo "0 results";
}
$T1->close();

 ?>