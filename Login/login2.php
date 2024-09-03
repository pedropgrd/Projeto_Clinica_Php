<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../css/login2.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .password-wrapper {
            position: relative;
        }

        #toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
<section class="area_login">
  <div class="login">
        <p class="line typing-animation">Bucal Saúde<i class="fa-solid fa-tree"></i></p>   
      <form action="" method="POST">
                <input type="text" name="cpf" id="cpf" placeholder="Digite seu CPF" title="Digite seu login">
                
                <div class="password-wrapper">
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha">
                    <span id="toggle-password" onclick="togglePasswordVisibility()">👁️</span>
                </div>
                
                <input class="b" id="entrar" name="entrar" type="submit" value="Entrar">
      </form>
      <a class="sair" href="../index.php">SAIR</a>
  </div>
</section>

<script>
function togglePasswordVisibility() {
    var passwordInput = document.getElementById('senha');
    var togglePassword = document.getElementById('toggle-password');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        togglePassword.textContent = '🙈'; // Altere o ícone se desejar
    } else {
        passwordInput.type = 'password';
        togglePassword.textContent = '👁️'; // Altere o ícone se desejar
    }
}
</script>
</body>
</html>

<?php
include '../conexao.php';
session_start();

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if (isset($_POST['entrar'])) {
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];

    if (empty($cpf) || empty($senha)) {
        echo "<script type='text/javascript' src='../js/jquery.js'></script>";
        echo "<script type='text/javascript' src='../js/sweetalert.js'></script>";
        echo "<link rel='stylesheet' href='../css/sweetalert.css'>";
        echo "<script type='text/javascript'>
        $(document).ready(function() {
            swal({
                title: 'Preencha os campos de login!',
                text: 'Favor colocar seu login e senha.',
                type: 'error',
                confirmButtonClass: 'btn btn-danger',
                confirmButtonText: 'Ok'
            }).then((result) => {
                if (result.value) {
                    window.location.href = 'login2.php';
                }
            });
        });
        </script>";
    } else {
        $stmt = $mysqli->prepare("SELECT cargo, admin, id_dentista, id_secretaria FROM usuarios WHERE cpf = ? AND senha = ?");
        $stmt->bind_param("ss", $cpf, $senha);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            $_SESSION['login_attempts'] += 1;
            echo "<script type='text/javascript' src='../js/jquery.js'></script>";
            echo "<script type='text/javascript' src='../js/sweetalert.js'></script>";
            echo "<link rel='stylesheet' href='../css/sweetalert.css'>";
            echo "<script type='text/javascript'>
            $(document).ready(function() {
                swal({
                    title: 'Login ou senha incorretos',
                    text: 'Favor colocar seu login e senha corretamente.',
                    type: 'error',
                    confirmButtonClass: 'btn btn-danger',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.value) {
                        window.location.href = 'login2.php';
                    }
                });
            });
            </script>";
        } else {
            $stmt->bind_result($cargo, $admin, $id_dentista, $id_secretaria);
            $stmt->fetch();
            $stmt->close();

            $_SESSION['login_attempts'] = 0;
            $_SESSION['cargo'] = $cargo;
            $_SESSION['admin'] = $admin;

            if ($cargo == "Dentista") {
                $_SESSION['id_dentista'] = $id_dentista;
            }

            if ($cargo == "Funcionario") {
                $_SESSION['id_secretaria'] = $id_secretaria;
                header("Location: tela_funcionario.php");
                exit();
            } elseif ($cargo == "Dentista") {
                header("Location: tela_dentistas.php");
                exit();
            }
        }
    }
}
?>
