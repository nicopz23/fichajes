<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = ($_POST['dni']);
    $password = $_POST['password'];
    $sql = "SELECT * FROM usuarios WHERE dni=? ";
    $consulta = $conn->prepare($sql);
    $consulta->bindParam(1,$dni);
    $consulta->execute();

    if ($consulta->rowCount() > 0) {
        $result = $consulta->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $result['password'])) {
            $nombre = $result['nombre'];
            $dni = $result['dni'];
            header("Location: datos?nombre=$nombre&dni=$dni");
        } else {
            $error =  "Contraseña incorrecta.";
            $_SESSION["error"] = $error;
        }
    } else {
        $error = "DNI no encontrado.";
        $_SESSION["error"] = $error;
    }
}