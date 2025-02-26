<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zona Diabética</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <!-- Sección de bienvenida -->
            <div class="col-md-5">
                <header class="text-center p-4 bg-light rounded">
                    <h1 class="mb-3">¡Bienvenido a tu zona Diabética!</h1>
                    <p class="lead">Encuentra productos y servicios para ayudarte a mantener tu salud y controlar tu metabolismo.</p>
                    <a href="https://www.diabetes.es/en/" class="btn btn-primary">Conocer más sobre Diabetes</a>
                </header>
            </div>

            <!-- Sección de inicio de sesión -->
            <div class="col-md-4">
                <div class="p-4 border rounded">
                    <h2 class="text-center">Iniciar sesión</h2>
                    <form method="POST" action="validar.php">
                        <?php
                            session_start();
                            if (isset($_SESSION["error"]) && $_SESSION["error"] == 1) {
                                //echo "<div class='alert alert-danger text-center'>Usuario o contraseña incorrectos</div>";
                            }
                        ?>

                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario:</label>
                            <input type="text" name="usu" class="form-control" required/>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña:</label>
                            <input type="password" name="pass" class="form-control" required/>
                        </div>
                        <div class="mb-3">
                             <p>¿No tienes cuenta? </p>
                             <a href="registro.php" class="text-primary">Regístrate</a>
                             <hr>
                        </div>
                        <div class="d-grid">
                            <input type="submit" name="enviar" value="Enviar" class="btn btn-success"/>
                        </div>
                    </form>
                </div>
            </div>    
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
