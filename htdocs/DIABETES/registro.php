
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
    <div class="container">
      <!--   <div class="row">
            <header class="col-12">
                <h1>Zona Diabéticos</h1>
                <nav class="navbar navbar-default navbar">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="nosotros.php">Nosotros</a></li>
                        <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
                    </ul>
                    <?php
                        if (isset($_SESSION['usuario'])) {
                            echo "<div class='navbar-text pull-right'>Hola, {$_SESSION['usuario']}</div>";
                        }
                   ?>
                </nav>
            </header>
        </div> -->
        <div class="row">
            <!-- Registrarse -->
             <div class="col-sm-5">
                <h2>Regístrate</h2>
                <form action="registro_validar.php" method="POST">
                    
                    <div class="form-group">
                        <label for="usuario">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaNac">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fechaNac" name="fechaNac" required>
                    </div>
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                    </div>
                    <div class="form-group">
                        <label for="contrasena_rep">Repetir Contraseña</label>
                        <input type="password" class="form-control" id="contrasena_rep" name="contrasena_rep" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Regístrate</button>
                </form>
                
                
             </div>     
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>