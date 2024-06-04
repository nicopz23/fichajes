<?php
include 'conexion.php';

$hoy = date('Y-m-d');

$sql = "SELECT usuarios.nombre, fichajes.fecha, fichajes.hora, fichajes.tipo 
FROM fichajes 
JOIN usuarios ON fichajes.usuario_id = usuarios.id 
WHERE fichajes.fecha = ? 
ORDER BY fichajes.hora DESC 
LIMIT 5";
$consulta = $conn->prepare($sql);
$consulta->bindParam(1, $hoy);
$consulta->execute();
$resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sistema de Fichaje</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
<style>
        body {
            background: url('https://source.unsplash.com/3Z70SDuYs5g/1920x1080') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            padding: 20px;
        }
        h2, h4 {
            color: white;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.8);
        }
        .btn-primary, .btn-primary:hover, .btn-primary:focus {
            background-color: #0062cc;
            border-color: #0056b3;
        }
        .table {
            color: white;
        }
        .table thead {
            background-color: #343a40;
        }
        .table td, .table th {
            background-color: rgba(52, 58, 64, 0.8);
        }
    </style>
    <div class="container mt-5">
        <h2 class="text-center">Fecha de hoy: <?php echo $hoy; ?></h2>
        <div class="row mt-4">
            <div class="col-md-4 offset-md-4">
                <h2 class="text-center">Iniciar sesión</h2>
                <form method="POST" action="validar">
                    <div class="form-group">
                        <label for="dni">DNI:</label>
                        <input type="text" class="form-control" id="dni" name="dni" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Validar</button>
                </form>
            </div>
        </div>
        <div class="table-responsive mt-5">
            <h4>Últimos Fichajes</h4>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Fecha y Hora</th>
                        <th scope="col">Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $fichajes) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fichajes['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($fichajes['fecha']) . ' ' . htmlspecialchars($fichajes['hora']); ?></td>
                            <td><?php echo htmlspecialchars($fichajes['tipo']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
