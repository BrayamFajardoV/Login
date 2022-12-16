<?php
    session_start();

    include_once('../Config/Conexion.php');

if (isset($_POST['Usuario']) && isset($_POST['NombreCompleto']) && isset($_POST['Clave']) && isset($_POST['RClave'])) {
    function validar($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }


    $usuario =  validar($_POST['Usuario']);
    $nombreCompleto =  validar($_POST['NombreCompleto']);
    $clave =  validar($_POST['Clave']);
    $rClave =  validar($_POST['RClave']);

    $usuario_Datos = 'Usuario=' . $usuario . '&NombreCompleto=' . $nombreCompleto;
    

    if (empty($usuario)) {
        header("location: ../Registrarse.php?error=El usuario es requerido&$usuarios_Datos");
        exit();
    }elseif (empty($nombreCompleto)) {
        header("location: ../Registrarse.php?error=El nombre completo es requerido&$usuarios_Datos");
        exit();
    }elseif (empty($clave)) {
        header("location: ../Registrarse.php?error=La clave es requerida&$usuarios_Datos");
        exit();
    }elseif (empty($rClave)) {
        header("location: ../Registrarse.php?error=Repetir la clave es requerida&$usuarios_Datos");
        exit();
    }elseif ($clave !== $rClave) {
        header("location: ../Registrarse.php?error=Las claves no coinciden&$usuarios_Datos");
        exit();
    }else {
        //hashing
        $clave = password_hash($clave, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM usuarios WHERE NombreUsuario = '$usuario'";
        $query = $conexion->query($sql);

        if (mysqli_num_rows($query) > 0) {
            header("location: ../Registrarse.php?error=El nombre de usuario ya existe&$usuarios_Datos");
            exit();
        }else {
            $sql2 = "INSERT INTO usuarios(NombreCompleto, NombreUsuario, Clave) VALUES ('$nombreCompleto','$usuario','$clave')";
            $query2 = $conexion->query($sql2);

            if ($query2) {
                header("location: ../Registrarse.php?success=Usuario creado con exito!&$usuarios_Datos");     
                exit();
            }else {
                header("location: ../Registrarse.php?error=Error desconocido&$usuarios_Datos");
                exit();
            }
        }
    }

}else {
    header("location: ../Registrarse.php");
    exit();
}