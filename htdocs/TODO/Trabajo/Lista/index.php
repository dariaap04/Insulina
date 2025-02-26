<?php
// Nombre del archivo donde se almacenarán las tareas
$filename = 'tasks.txt';

// Función para cargar las tareas desde el archivo
function loadTasks($filename) {
    if (file_exists($filename)) {
        $tasks = file($filename, FILE_IGNORE_NEW_LINES);
    } else {
        $tasks = [];
    }
    return $tasks;
}

// Función para guardar las tareas en el archivo
function saveTasks($filename, $tasks) {
    file_put_contents($filename, implode(PHP_EOL, $tasks));
}

// Inicializar lista de tareas
$tasks = loadTasks($filename);

// Añadir una nueva tarea
if (isset($_POST['new_task']) && !empty(trim($_POST['new_task']))) {
    $new_task = htmlspecialchars(trim($_POST['new_task']));
    $tasks[] = $new_task;
    saveTasks($filename, $tasks);
    header("Location: index.php");
    exit();
}

// Eliminar una tarea
if (isset($_GET['delete'])) {
    $index = (int)$_GET['delete'];
    if (isset($tasks[$index])) {
        unset($tasks[$index]);
        $tasks = array_values($tasks); // Reindexar la lista
        saveTasks($filename, $tasks);
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Tareas</title>
</head>
<body>
    <h1>Lista de Tareas</h1>

    <!-- Formulario para añadir una nueva tarea -->
    <form method="POST" action="">
        <input type="text" name="new_task" placeholder="Nueva tarea" required>
        <button type="submit">Añadir</button>
    </form>

    <h2>Tareas Pendientes</h2>
    <ul>
        <?php if (empty($tasks)): ?>
            <li>No hay tareas pendientes</li>
        <?php else: ?>
            <?php foreach ($tasks as $index => $task): ?>
                <li>
                    <?php echo htmlspecialchars($task); ?>
                    <a href="?delete=<?php echo $index; ?>" onclick="return confirm('¿Estás seguro de eliminar esta tarea?');">Eliminar</a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</body>
</html>
