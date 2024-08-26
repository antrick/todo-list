<?php
require_once '../src/Task.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$task = new Task();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $status = $_POST['status'];
        $task->create($userId, $title, $description, $status);
    } elseif (isset($_POST['update'])) {
        $taskId = $_POST['task_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $status = $_POST['status'];
        $task->update($taskId, $title, $description, $status);
    } elseif (isset($_POST['delete'])) {
        $taskId = $_POST['task_id'];
        $task->delete($taskId);
    }
}

$tasks = $task->getAll($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col d-flex justify-content-start">
            <h2 class="mb-4">Tareas</h2>
        </div>
        <div class="col d-flex justify-content-end">
            <p><a href="logout.php" class="btn btn-outline-secondary">Logout</a></p>
        </div>
    </div>
    <!-- Form to add new task -->
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title">Agregar Nueva Tarea</h4>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <input type="hidden" name="create" value="1">
                <div class="mb-3">
                    <label for="title" class="form-label">Título: </label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción:</label>
                    <textarea name="description" id="description" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Estado:</label>
                    <select name="status" id="status" class="form-select">
                        <option value="pendiente">Pendiente</option>
                        <option value="en progreso">En progreso</option>
                        <option value="completado">Completado</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Tarea</button>
            </form>
        </div>
    </div>

  <!-- Task List -->
  <h3 class="mb-6">Lista de Tareas</h3>
        <div class="task-list my-4">
            <?php if ($tasks->num_rows > 0): ?>
                <?php while ($row = $tasks->fetch_assoc()): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <form method="post" action="">
                                <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                                <div class="mb-3">
                                    <label for="title_<?php echo $row['id']; ?>" class="form-label">Título:</label>
                                    <input type="text" name="title" id="title_<?php echo $row['id']; ?>" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description_<?php echo $row['id']; ?>" class="form-label">Descripción:</label>
                                    <textarea name="description" id="description_<?php echo $row['id']; ?>" class="form-control"><?php echo htmlspecialchars($row['description']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="status_<?php echo $row['id']; ?>" class="form-label">Estado:</label>
                                    <select name="status" id="status_<?php echo $row['id']; ?>" class="form-select">
                                        <option value="pendiente" <?php echo $row['status'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                        <option value="en progreso" <?php echo $row['status'] === 'en progreso' ? 'selected' : ''; ?>>En Progreso</option>
                                        <option value="completado" <?php echo $row['status'] === 'completado' ? 'selected' : ''; ?>>Completado</option>
                                    </select>
                                </div>
                                <button type="submit" name="update" class="btn btn-success">Actualizar</button>
                                <button type="submit" name="delete" class="btn btn-danger ms-2">Borrar</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    No tasks found.
                </div>
            <?php endif; ?>
        </div>

    <!-- <p><a href="logout.php" class="btn btn-outline-secondary">Logout</a></p> -->
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>