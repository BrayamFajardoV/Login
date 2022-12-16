<?php

    session_start();

    include_once('../Config/Conexion.php');

     if (isset($_POST['Usuario']) && isset($_POST['Clave'])) {
        
        function Validar($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);

            return $data;
        }

        $usuario = Validar($_POST['Usuario']);
        $clave = Validar($_POST['Clave']);

        if (empty($usuario)) {
            header('location: ../Index.php?error=El usuario es requerido');
            exit();
        }elseif (empty($clave)) {
            header('location: ../Index.php?error=La clave es requerida');
            exit();
        }else {
            $Sql = "SELECT * FROM usuarios WHERE NombreUsuario = '$usuario'";
            $query = mysqli_query($conexion, $Sql);

            if ($query->num_rows==1) {
                $usuarioQ = $query->fetch_assoc();

                $Id = $usuarioQ['Id'];
                $NombreUsuario = $usuarioQ['NombreUsuario'];
                $Clave = $usuarioQ['Clave'];
                $NombreCompleto = $usuarioQ['NombreCompleto'];


                if ($usuario === $NombreUsuario) {
                    if (password_verify($clave, $Clave)) {
                        $_SESSION['Id'] = $Id;
                        $_SESSION['NombreUsuario'] = $NombreUsuario;
                        $_SESSION['NombreCompleto'] = $NombreCompleto;

                        echo "<script>
                            alert('Bienvenido $NombreCompleto');
                            location.href = '../Home.php'
                        </script>";
                    }else {
                        header('Location:../Index.php?error=Usuario o clave incorrecta');
                    }
                }else {
                    header('Location:../Index.php?error=Usuario o clave incorrecta');
                }  
            }else {
                header('Location:../Index.php?error=Usuario o clave incorrecta');
            }
        }
    }