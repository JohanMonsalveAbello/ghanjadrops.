<?php
// Establecer conexión a la base de datos (reemplaza estos valores con los tuyos)
$servername = "ghanjadrops.mysql.database.azure.com";
$username = "johan";
$password = "MONSALVE#2006";
$dbname = "ghanjadrops";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token']; // Este token debe ser enviado desde el formulario junto con la nueva contraseña
    $nueva_contraseña = $_POST['nueva_contraseña']; // La nueva contraseña ingresada por el usuario
    $IDD=$_POST['NumeroDeIdentificacion'];
    $CCorreo=$_POST['correo'];

    // Verificar si el token existe en tu base de datos
    // Aquí deberías tener una lógica para verificar si el token es válido y está asociado al correo electrónico
    
    // Encriptar la nueva contraseña antes de almacenarla en la base de datos (opcional pero altamente recomendado)
    $hashedPassword = password_hash($nueva_contraseña, PASSWORD_DEFAULT);

    // Consulta SQL para actualizar la contraseña del usuario asociado a este token
    $sql = "UPDATE usuarios SET contracena = '$hashedPassword' WHERE NumeroDeIdentificacion = '$IDD' AND correo = '$CCorreo'";


    if ($conn->query($sql) === TRUE) {
        
        include("index.html");
        echo "<script src='sweetAlertDD3.js'></script>";
    } else {
        include("index.html");
        echo "<script src='sweetAlertDD3.js'></script>";
    }
} else {
    echo "Acceso denegado";
}

// Cerrar conexión
$conn->close();

?>
