<?php
include '../conexao.php';

if (isset($_POST['cadastrar'])) {
    $nome = $_POST['name'];
    $email = $_POST['email'];
    $cargo = $_POST['select'];
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];
    $admin = isset($_POST['admin']) ? $_POST['admin'] : 'N';

    if (empty($nome) || empty($email) || empty($cargo) || empty($cpf) || empty($senha)) {
        echo "<script>alert('Por favor, preencha todos os campos'); window.location.href='cadastro_login.php';</script>";
        die();
    }

    if ($cargo == "Dentista") {
        $stmt = $mysqli->prepare("INSERT INTO dentista (nome, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $email);
        $stmt->execute();
        $id_dentista = $stmt->insert_id;
        $stmt->close();

        $stmt = $mysqli->prepare("INSERT INTO usuarios (cpf, senha, cargo, id_dentista, admin) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $cpf, $senha, $cargo, $id_dentista, $admin);
    } elseif ($cargo == "Funcionario") {
        $stmt = $mysqli->prepare("INSERT INTO secretaria (nome, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $email);
        $stmt->execute();
        $id_secretaria = $stmt->insert_id;
        $stmt->close();

        $stmt = $mysqli->prepare("INSERT INTO usuarios (cpf, senha, cargo, id_secretaria, admin) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $cpf, $senha, $cargo, $id_secretaria, $admin);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='cadastro_login.php';</script>";
    } else {
        echo "<script>alert('Não foi possível cadastrar esse usuário'); window.location.href='cadastro_login.php';</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Tooplate">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/animate.css">
    <link rel="stylesheet" href="../css/owl.carousel.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../css/tooplate-style.css">

    <title>Cadastro Usuário</title>
</head>
<body>
    <section id="appointment" data-stellar-background-ratio="3">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <img src="../images/appointment-image.jpg" class="img-responsive" alt="">
                </div>
                <div class="col-md-6 col-sm-6">
                    <form id="appointment-form" role="form" method="post" action="">
                        <div class="section-title wow fadeInUp" data-wow-delay="0.4s" id="news">
                            <h2>Cadastro de usuário</h2>
                        </div>
                        <div class="wow fadeInUp" data-wow-delay="0.8s">
                            <div class="col-md-6 col-sm-6">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Digite seu nome..">
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Seu E-mail">
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="select">Cargo</label>
                                <select class="form-control" name="select">
                                    <option value="Funcionario">Funcionario</option>
                                    <option value="Dentista">Dentista</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="admin">Admin</label>
                                <select class="form-control" name="admin">
                                    <option value="N">Não</option>
                                    <option value="S">Sim</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="cpf">Login</label>
                                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" title="Digite o CPF">
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="senha">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha..">
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <button type="submit" class="form-control" id="cadastrar" name="cadastrar">Cadastrar</button>
                            </div>
                        </div>
                    </form>
                    <form method="get" action="consulta.login.php">
                        <button type="submit" class="form-control">Consultar Usuários</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
