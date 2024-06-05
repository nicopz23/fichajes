<?php
include 'conexion.php';

if (!isset($_GET['nombre'])) {
    header("Location: index.php");
    exit();
}

$nombre = $_GET['nombre'];
$dni = $_GET['dni'];

$sql = "SELECT * FROM usuarios WHERE nombre=? and dni=?";
$consulta = $conn->prepare($sql);
$consulta->bindParam(1, $nombre);
$consulta->bindParam(2, $dni);
$consulta->execute();
$result = $consulta->fetch(PDO::FETCH_ASSOC);
$id = $result["id"];

if (isset($_POST["a"])) {
    $hoy = date('Y-m-d');
    $sql_consulta = 'select * from fichajes where usuario_id = ? and fecha= ?';

    $consulta_fichaje = $conn->prepare($sql_consulta);
    $consulta_fichaje->bindParam(1, $id);
    $consulta_fichaje->bindParam(2, $hoy);
    $consulta_fichaje->execute();
    if ($consulta_fichaje->rowCount() > 0) {
        $error = 'Ya hay un fichaje de entrada del dia de hoy';
    } else {
        $tipo = 'entrada';
        $sql_insert = 'INSERT INTO fichajes (usuario_id,tipo) values (?,?)';
        $insert = $conn->prepare($sql_insert);
        $insert->bindParam(1, $id);
        $insert->bindParam(2, $tipo);
        $insert->execute();
        if ($insert->rowCount() > 0) {
            header("Location: ./");
        } else {
            $error = 'no se inserto entrada';
        }
    }
}

if (isset($_POST["b"])) {
    $hoy = date('Y-m-d');
    $tipo = 'salida';
    $sql_consulta = 'select * from fichajes where usuario_id = ? and fecha= ?';

    $consulta_fichaje = $conn->prepare($sql_consulta);
    $consulta_fichaje->bindParam(1, $id);
    $consulta_fichaje->bindParam(2, $hoy);
    $consulta_fichaje->execute();
    if ($consulta_fichaje->rowCount() > 0) {
        $subconsulta = 'select * from fichajes where usuario_id = ? and fecha = ? and tipo = ?';
        $sub = $conn->prepare($subconsulta);
        $sub->bindParam(1, $id);
        $sub->bindParam(2, $hoy);
        $sub->bindParam(3, $tipo);
        $sub->execute();
        if ($sub->rowCount() > 0) {
            $error = 'Ya fichaste una salida';
        } else {
            $sql_insert = 'INSERT INTO fichajes (usuario_id,tipo) values (?,?)';
            $insert = $conn->prepare($sql_insert);
            $insert->bindParam(1, $id);
            $insert->bindParam(2, $tipo);
            $insert->execute();
            if ($insert->rowCount() > 0) {
                header("Location: ./");
            } else {
                $error = 'no se inserto salida';
            }
        }
    } else {
        $error = 'No hay un fichaje de entrada del dia de hoy';
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Datos del Usuario</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: url('https://source.unsplash.com/3Z70SDuYs5g/1920x1080') no-repeat center center fixed;
            background-size: cover;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 15px;
            padding: 20px;
            width: 100%;
           
        }

        .card {
            background-color: rgba(0, 0, 0, 0.6);
            border: none;
        }

        .card-body {
            padding: 20px;
        }

        h2,
        h3,
        h5 {
            color: white;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.8);
        }

        .btn-primary,
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-danger,
        .btn-danger:hover,
        .btn-danger:focus {
            background-color: #0062cc;
            border-color: #0056b3;
        }

        .btn-danger,
        .btn-danger:hover,
        .btn-danger:focus {
            background-color: #dc3545;
            border-color: #bd2130;
        }

        .text-danger {
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Bienvenido, <?php echo htmlspecialchars($nombre); ?></h2>
                <h5 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($dni); ?></h5>
                <p class="text-danger"><?php if (isset($error)) {
                                            echo $error;
                                            echo '<p class="text-danger">Dentro de 3 segundos se reiniciara la pagina </p> ';
                                            echo '<script>
                                                    setTimeout(function(){
                                                        window.location.href = "./";
                                                    }, 3000);
                                                </script>';
                                        } ?></p>
                <h3 class="mt-4">Fichar</h3>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <form action="" method="post">
                            <button type="submit" name="a" class="btn btn-primary btn-block">Fichar Entrada</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="" method="post">
                            <button type="submit" name="b" class="btn btn-danger btn-block">Fichar Salida</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
