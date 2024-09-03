<?php
include '../conexao.php';

// Verificação se o formulário foi submetido
if (isset($_POST['cadastrar'])) {
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];

    if (empty($descricao) || empty($preco)) {
        echo "<script>alert('Por favor, preencha todos os campos'); window.location.href='tipo_consulta.php';</script>";
        die();
    }

    $stmt = $mysqli->prepare("INSERT INTO tipo_consulta (descricao, preco) VALUES (?, ?)");
    $stmt->bind_param("sd", $descricao, $preco);

    if ($stmt->execute()) {
        echo "<script>alert('Tipo de consulta cadastrado com sucesso!'); window.location.href='consulta_tipo_consulta.php';</script>";
    } else {
        echo "<script>alert('Não foi possível cadastrar o tipo de consulta'); window.location.href='tipo_consulta.php';</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/animate.css">
    <link rel="stylesheet" href="../css/owl.carousel.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../css/tooplate-style.css">

    <title>Cadastro de Tipo de Consulta</title>
</head>
<body>
    <section id="appointment" data-stellar-background-ratio="3">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <img src="../images/appointment-image.jpg" class="img-responsive" alt="">
                </div>
                <div class="col-md-6 col-sm-6">
                    <form id="appointment-form" role="form" method="post" action="tipo_consulta.php">
                        <div class="section-title wow fadeInUp" data-wow-delay="0.4s" id="news">
                            <h2>Cadastro de Tipo de Consulta</h2>
                        </div>
                        <div class="wow fadeInUp" data-wow-delay="0.8s">
                            <div class="col-md-6 col-sm-6">
                                <label for="descricao">Descrição</label>
                                <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição da consulta" required>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="preco">Preço</label>
                                <input type="number" class="form-control" id="preco" name="preco" step="0.01" placeholder="Preço da consulta" required>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <button type="submit" class="form-control" id="cadastrar" name="cadastrar">Cadastrar</button>
                            </div>
                        </div>
                    </form>
                    <form method="get" action="consulta_tipo_consulta.php">
                        <button type="submit" class="form-control">Consultar Tipos de Consulta</button>
                    </form>
                    <form method="get" action="tela_funcionario.php">
                        <button type="submit" class="form-control">Voltar</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
