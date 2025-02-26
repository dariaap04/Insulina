<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// Cargar variables del archivo info.env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, 'info.env');
$dotenv->load();

$smtp_user = $_ENV['SMTP_USER'] ?? null;
$smtp_pass = $_ENV['SMTP_PASS'] ?? null;

if (!$smtp_user || !$smtp_pass) {
    die("Error: No se pudieron cargar las credenciales SMTP.");
}

// Configuración de la base de datos
$servername = "localhost";
$username = "root";  
$password = "";  
$database = "reservas_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accion'], $_POST['nombreCliente'], $_POST['id'], $_POST['emailCliente'])) {
        $accion = $_POST['accion'];
        $nombreCliente = htmlspecialchars($_POST['nombreCliente']);
        $id = intval($_POST['id']);
        $emailCliente = filter_var($_POST['emailCliente'], FILTER_VALIDATE_EMAIL);

        if (!$emailCliente) {
            $mensaje = "Error: Dirección de correo inválida.";
            header("Location: gestionar_reservas.php?mensaje=" . urlencode($mensaje));
            exit();
        }

        $nuevo_estado = ($accion == 'aceptar') ? 'Aceptada' : 'Rechazada';

        $stmt = $conn->prepare("UPDATE reservas SET estado = ? WHERE id = ?");
        $stmt->bind_param("si", $nuevo_estado, $id);

        if ($stmt->execute()) {
            // Correo al cliente
            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $smtp_user;
                $mail->Password = $smtp_pass;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Configuración del correo
                $mail->setFrom($smtp_user, 'Restaurante El Infante');
                $mail->addAddress($emailCliente, $nombreCliente);

                $mail->isHTML(true);
                $mail->Subject = 'Estado de su reserva';
                $mail->Body = '
                    <!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Estado de su reserva</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f4f4f4;
                                margin: 0;
                                padding: 20px;
                            }
                            .container {
                                max-width: 600px;
                                margin: auto;
                                background-color: #ffffff;
                                padding: 20px;
                                border-radius: 10px;
                                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                            }
                            .header {
                                text-align: center;
                                background-color: #6fa3ef;
                                padding: 20px;
                                color: white;
                                border-radius: 10px;
                            }
                            .header h1 {
                                margin: 0;
                            }
                            .message {
                                margin-top: 20px;
                                font-size: 16px;
                                color: #333333;
                            }
                            .footer {
                                margin-top: 30px;
                                text-align: center;
                                font-size: 14px;
                                color: #777777;
                            }
                            .button {
                                background-color: #6fa3ef;
                                color: white;
                                padding: 10px 20px;
                                text-decoration: none;
                                border-radius: 5px;
                                display: inline-block;
                                margin-top: 20px;
                            }
                            .button:hover {
                                background-color: #4f7dcd;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>Restaurante El Infante</h1>
                            </div>
                            <div class="message">
                                <p>Estimado/a <strong>' . $nombreCliente . '</strong>,</p>
                                <p>Le informamos que su reserva ha sido <strong>' . $nuevo_estado . '</strong>.</p>
                                <p>Gracias por su confianza. Si desea realizar otra reserva, no dude en contactarnos.</p>
                                <a href="http://localhost/RESERVAS" class="button">Visite nuestro sitio web</a>
                            </div>
                            <div class="footer">
                                <p>Saludos cordiales,</p>
                                <p>Restaurante El Infante</p>
                            </div>
                        </div>
                    </body>
                    </html>
                    ';
                $mail->AltBody = "Estimado/a $nombreCliente, su reserva ha sido $nuevo_estado. Gracias por su confianza.";
                $mail->send();
                $mensaje = 'Correo enviado exitosamente a ' . htmlspecialchars($emailCliente);
            } catch (Exception $e) {
                $mensaje = "No se pudo enviar el correo. Error: " . htmlspecialchars($mail->ErrorInfo);
            }

            // Correo al administrador
            $admin_email = 'elinfantesidreria@gmail.com'; // Cambia esta dirección con la del administrador
            $admin_subject = 'Nueva reserva en el Restaurante El Infante';

            $mail_admin = new PHPMailer(true);
            try {
                $mail_admin->isSMTP();
                $mail_admin->Host = 'smtp.gmail.com';
                $mail_admin->SMTPAuth = true;
                $mail_admin->Username = $smtp_user;
                $mail_admin->Password = $smtp_pass;
                $mail_admin->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail_admin->Port = 587;

                $mail_admin->setFrom($smtp_user, 'Restaurante El Infante');
                $mail_admin->addAddress($admin_email);  // Correo del administrador

                $mail_admin->isHTML(true);
                $mail_admin->Subject = $admin_subject;
                $mail_admin->Body = '
                <!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Nueva Reserva</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            margin: 0;
                            padding: 20px;
                        }
                        .container {
                            max-width: 600px;
                            margin: auto;
                            background-color: #ffffff;
                            padding: 20px;
                            border-radius: 10px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
                        .header {
                            text-align: center;
                            background-color: #6fa3ef;
                            padding: 20px;
                            color: white;
                            border-radius: 10px;
                        }
                        .header h1 {
                            margin: 0;
                        }
                        .message {
                            margin-top: 20px;
                            font-size: 16px;
                            color: #333333;
                        }
                        .footer {
                            margin-top: 30px;
                            text-align: center;
                            font-size: 14px;
                            color: #777777;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h1>Restaurante El Infante</h1>
                        </div>
                        <div class="message">
                            <p><strong>Estimado/a Administrador/a,</strong></p>
                            <p>Ha recibido una nueva reserva. Aquí están los detalles:</p>
                            <ul>
                                <li><strong>Cliente:</strong> ' . $nombreCliente . '</li>
                                <li><strong>Email:</strong> ' . $emailCliente . '</li>
                                <li><strong>Estado de la reserva:</strong> ' . $nuevo_estado . '</li>
                            </ul>
                            <p>Por favor, proceda con la gestión de la reserva en el sistema.</p>
                        </div>
                        <div class="footer">
                            <p>Saludos cordiales,</p>
                            <p>Restaurante El Infante</p>
                        </div>
                    </div>
                </body>
                </html>';
                $mail_admin->AltBody = "Estimado/a Administrador/a, ha recibido una nueva reserva. Cliente: $nombreCliente, Email: $emailCliente, Estado: $nuevo_estado.";

                $mail_admin->send();
            } catch (Exception $e) {
                $mensaje = "No se pudo enviar el correo al administrador. Error: " . htmlspecialchars($mail_admin->ErrorInfo);
            }

        } else {
            $mensaje = "Error al actualizar el estado.";
        }

        $stmt->close();
    } else {
        $mensaje = "Error: Parámetros inválidos.";
    }

    header("Location: gestionar_reservas.php?mensaje=" . urlencode($mensaje));
    exit();
}

$conn->close();
?>
