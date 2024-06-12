<?php
// Incluir la biblioteca PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Si usas Composer

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
    $email = $_POST['email']; // Obtener el correo electrónico proporcionado por el usuario

    // Consulta SQL para verificar si el correo existe en la tabla de usuarios
    $sql = "SELECT correo FROM usuarios WHERE correo = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // El correo existe en la base de datos, proceder con el envío del correo de recuperación
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $subject = "Recuperación de contraseña";
        $message = "Su código de verificación o token es: $token. Para restablecer tu contraseña, haz clic en este enlace: https://ghanjadrops.azurewebsites.net/nueva_contraseña.html?token=$token";

        // Configurar PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP de Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ghanjadropselmejorproyecto2023@gmail.com'; 
            $mail->Password = 'fmub zjfi rlil metj'; 
            $mail->SMTPSecure = 'tls'; // Cambiado a TLS
            $mail->Port = 587;

            // Configuración adicional para verificar el certificado
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // Remitente y destinatario
            $mail->setFrom('ghanjadropselmejorproyecto2023@gmail.com', 'GhanjaDrops');
            $mail->addAddress($email);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = "<p>Su código de verificación o token es: <strong>$token</strong>. Para restablecer tu contraseña, haz clic en este enlace: <a href='https://127.0.0.1/GHANJADROPS/nueva_contraseña.html?token=$token'>Restablecer contraseña</a></p>";
            $mail->AltBody = "Su código de verificación o token es: $token. Para restablecer tu contraseña, haz clic en este enlace: https://ghanjadrops.azurewebsites.net/nueva_contraseña.html?token=$token";

            $mail->send();
            // Mostrar alerta de éxito
            include("./recuperarcontraseña.html");
            echo "<script src='https://ghanjadrops.azurewebsites.net/sweetAlertDD2.js'></script>";
        } catch (Exception $e) {
            echo "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        // Mostrar alerta si el correo no está registrado
        include("./recuperarcontraseña.html");
        echo "<script src='https://ghanjadrops.azurewebsites.net/sweetAlert3.js'></script>";
    }
} else {
    // Mostrar alerta si el método no es permitido
    include("./recuperarcontraseña.html");
    echo "<script src='sweetAlert.js'></script>";
}

// Cerrar conexión
$conn->close();
?>
