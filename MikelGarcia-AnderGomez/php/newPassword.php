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
    <?php
    include 'DbConfig.php';
    $cod = $_GET['cod'];
    $mysqli = mysqli_connect($server, $user, $pass, $basededatos);
    if (!$mysqli) {
        echo ('MAL');
        die('Fallo al conectar a MySQL: ' . mysqli_connect_error());
    }
    $query = $mysqli->prepare("SELECT 'cod' FROM users WHERE cod = ?");
    $query->bind_param("i", $cod);
    if ($query->execute()) {
        $result = $query->get_result();
        if ($result->num_rows === 0) {
            echo '<section class="main" id="s1">';
            echo 'Error al cambiar contraseña';
            echo '</section>';
        } else {
            $query = mysqli_query($mysqli, "SELECT email FROM users WHERE cod = $cod");
            while ($row = mysqli_fetch_array($query)) {
                $email =  htmlspecialchars($row['email']);
            }
            echo '<section class="main" id="s1">';
            echo '<h3>Bienvenido ' . $email . '</h3>';
            echo '<br>';
            echo '<form id="newPass" method="POST" action="newPassword.php?cod=' . $cod . '">
                    <table style="margin: 0px auto">
                    <tr><td align="left">
                    <label>Introduzca nueva contraseña: </label>
                    </td><td>
                    <input type="password" id="pass" name="pass" onblur="passValid(this.value)">
                    </td>
                    <td><label id="PASS" name="PASS"></label></tr>
                    <tr><td align="left">
                    <label>Repita la contraseña: </label>
                    </td><td>
                    <input type="password" id="pass2" name="pass2">
                    </table>
                    <input type="submit" id="Registrarse" name="Registrarse" value="Cambiar Contraseña" disabled="true">
                </form>';
            echo '<script src="../js/jquery-3.4.1.min.js"></script>';
            echo '<script src="../js/ContrasenaValid.js"></script>';
            echo '<script>
                    function poderRegistrarse() {
                        if ($("#PASS").text() == "Contraseña Valida") {
                        $("#Registrarse").prop("disabled", false);
                        }    
                    }
                    setInterval(poderRegistrarse, 1000);
                </script>';

            if (isset($_POST['pass']) && isset($_POST['pass2'])) {
                if (empty($_POST['pass']) || empty($_POST['pass2'])) {
                    echo '<br>';
                    echo 'Rellene los campos';
                } else {
                    if ($_POST['pass'] != $_POST['pass2']) {
                        echo '<br>';
                        echo 'Las contraseñas deben ser iguales';
                    } else {
                        $salt = $email . "#Vadillo007STONKS";
                        $contraseñasegura = crypt($_POST['pass'], $salt);
                        $query = $mysqli->prepare("UPDATE users SET password = (?)");
                        $query->bind_param('s', $contraseñasegura);
                        if (!$query->execute())
                            die("Error: No se ha podido añadir a la base de datos" . mysqli_error($mysqli));
                        echo '<br>';
                        echo "<script>alert('Contraseña Actualizada'); location.href='LogIn.php'; </script>";
                    }
                }
            }

            echo '</section>';
        }
    }
    mysqli_close($mysqli);
    ?>
    <?php include '../html/Footer.html' ?>
</body>

</html>