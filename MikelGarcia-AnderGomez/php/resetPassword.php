<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include '../html/Head.html' ?>
</head>

<body>
    <?php include '../php/Menus.php' ?>
    <section class="main" id="s1">
        <h2>Recuperar contraseña</h2>
        <br>
        <form id='resPass' method="POST" action="resetPassword.php">
            <label>Introduzco su email: </label>
            <input type="text" id="mail" name="mail">
            <br><br>
            <input type="submit" value="Enviar email">
        </form>

        <?php
        include 'DbConfig.php';
        if (isset($_POST['mail'])) {
            if (empty($_POST['mail'])) {
                echo 'Introduzca un email';
            } else {
                $mysqli = mysqli_connect($server, $user, $pass, $basededatos);
                if (!$mysqli) {
                    echo ('MAL');
                    die('Fallo al conectar a MySQL: ' . mysqli_connect_error());
                }
                $query = $mysqli->prepare("SELECT 'mail' FROM users WHERE email = ?");
                $query->bind_param("s", $_POST['mail']);
                if ($query->execute()) {
                    $result = $query->get_result();
                    if ($result->num_rows === 0)
                        echo 'No existe usuario con dicho email';
                    else {
                        $mail = $_POST['mail'];
                        $cod = random_int(10000000, 99999999);
                        $query = $mysqli->prepare("UPDATE users SET cod = '" . $cod . "' WHERE email = '" . $mail . "'");
                        if (!$query->execute())
                            die('Error: No se ha podido añadir a la base de datos' . mysqli_error($mysqli));
                        $mensaje = 'https://garcitxiki.000webhostapp.com/MikelGarcia-AnderGomez/php/newPassword.php?cod=' . $cod;
                        echo $mensaje;
                        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
                        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        mail($mail, 'Restablecer contraseña', $mensaje, $cabeceras);
                    }
                }
                mysqli_close($mysqli);
            }
        }

        ?>
    </section>
    <?php include '../html/Footer.html' ?>
</body>

</html>