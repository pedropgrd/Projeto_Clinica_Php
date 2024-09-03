<?php
include '../conexao.php';

// Inicialize variáveis
$id_agenda = $id_paciente = $id_dentista = $id_tipo_consulta = $tipo_consulta = $descricao = $realizado = $horario = $dt_agenda = $preco_final_agendamento = '';

// Se for uma edição, preencha os campos com os dados existentes
if (isset($_GET['id'])) {
    $id_agenda = $_GET['id'];
    $sql = "SELECT * FROM agendamento WHERE id_agenda = $id_agenda";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_paciente = $row['id_paciente'];
        $id_dentista = $row['id_dentista'];
        $id_tipo_consulta = $row['id_tipo_consulta'];
        $tipo_consulta = $row['tipo_consulta'];
        $descricao = $row['descricao'];
        $realizado = $row['realizado'];
        $horario = $row['horario'];
        $dt_agenda = $row['dt_agenda'];
        $preco_final_agendamento = $row['preco_final_agendamento'];
    }
}

// Adicionar ou atualizar agendamento
if (isset($_POST['save'])) {
    $id_paciente = $_POST['id_paciente'];
    $id_dentista = $_POST['id_dentista'];
    $id_tipo_consulta = $_POST['id_tipo_consulta'];
    $tipo_consulta = $_POST['tipo_consulta'];
    $descricao = $_POST['descricao'];
    $realizado = $_POST['realizado'];
    $horario = $_POST['horario'];
    $dt_agenda = $_POST['dt_agenda'];
    $preco_final_agendamento = $_POST['preco_final_agendamento'];

    if ($id_agenda) {
        $sql = "UPDATE agendamento SET id_paciente='$id_paciente', id_dentista='$id_dentista', id_tipo_consulta='$id_tipo_consulta', tipo_consulta='$tipo_consulta', descricao='$descricao', realizado='$realizado', horario='$horario', dt_agenda='$dt_agenda', preco_final_agendamento='$preco_final_agendamento' WHERE id_agenda=$id_agenda";
    } else {
        $sql = "INSERT INTO agendamento (id_paciente, id_dentista, id_tipo_consulta, tipo_consulta, descricao, realizado, horario, dt_agenda, preco_final_agendamento) VALUES ('$id_paciente', '$id_dentista', '$id_tipo_consulta', '$tipo_consulta', '$descricao', '$realizado', '$horario', '$dt_agenda', '$preco_final_agendamento')";
    }

    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Agendamento salvo com sucesso'); window.location.href='consulta_agendamento.php';</script>";
    } else {
        echo "<script>alert('Erro ao salvar agendamento: " . $mysqli->error . "');</script>";
    }
}

// Buscar dentistas
$dentistas = [];
$sql = "SELECT id_dentista, nome FROM dentista";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dentistas[] = $row;
    }
}

// Buscar pacientes
$pacientes = [];
$sql = "SELECT id_paciente, nome FROM paciente";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pacientes[] = $row;
    }
}

// Buscar tipos de consulta
$tipos_consulta = [];
$sql = "SELECT id_tipo_consulta, descricao FROM tipo_consulta";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tipos_consulta[] = $row;
    }
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

    <title>Cadastro de Agendamento</title>
</head>
<body>
    <section id="appointment" data-stellar-background-ratio="3">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <img src="../images/appointment-image.jpg" class="img-responsive" alt="">
                </div>
                <div class="col-md-6 col-sm-6">
                    <form id="appointment-form" role="form" method="post">
                        <div class="section-title wow fadeInUp" data-wow-delay="0.4s" id="news">
                            <h2><?php echo $id_agenda ? 'Editar Agendamento' : 'Novo Agendamento'; ?></h2>
                        </div>
                        <div class="wow fadeInUp" data-wow-delay="0.8s">
                            <input type="hidden" name="id_agenda" value="<?php echo $id_agenda; ?>">

                            <!-- Selecionar Paciente -->
                            <div class="col-md-6 col-sm-6">
                                <label for="id_paciente">Paciente</label>
                                <select class="form-select" id="id_paciente" name="id_paciente" required>
                                    <option value="">Selecione um paciente</option>
                                    <?php foreach ($pacientes as $paciente): ?>
                                        <option value="<?php echo $paciente['id_paciente']; ?>" <?php echo $id_paciente == $paciente['id_paciente'] ? 'selected' : ''; ?>>
                                            <?php echo $paciente['nome']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Selecionar Dentista -->
                            <div class="col-md-6 col-sm-6">
                                <label for="id_dentista">Dentista</label>
                                <select class="form-select" id="id_dentista" name="id_dentista" required>
                                    <option value="">Selecione um dentista</option>
                                    <?php foreach ($dentistas as $dentista): ?>
                                        <option value="<?php echo $dentista['id_dentista']; ?>" <?php echo $id_dentista == $dentista['id_dentista'] ? 'selected' : ''; ?>>
                                            <?php echo $dentista['nome']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Selecionar Tipo de Consulta -->
                            <div class="col-md-6 col-sm-6">
                                <label for="id_tipo_consulta">Tipo de Consulta</label>
                                <select class="form-select" id="id_tipo_consulta" name="id_tipo_consulta" required>
                                    <option value="">Selecione um tipo de consulta</option>
                                    <?php foreach ($tipos_consulta as $tipo): ?>
                                        <option value="<?php echo $tipo['id_tipo_consulta']; ?>" <?php echo $id_tipo_consulta == $tipo['id_tipo_consulta'] ? 'selected' : ''; ?>>
                                            <?php echo $tipo['descricao']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <label for="tipo_consulta">Tipo de Consulta (Descrição)</label>
                                <input type="text" class="form-control" id="tipo_consulta" name="tipo_consulta" value="<?php echo $tipo_consulta; ?>" required>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="descricao">Descrição</label>
                                <input type="text" class="form-control" id="descricao" name="descricao" value="<?php echo $descricao; ?>" required>
                            </div>

                            <!-- Campo "Realizado" atualizado -->
                            <div class="col-md-6 col-sm-6">
                                <label for="realizado">Realizado</label>
                                <select class="form-select" id="realizado" name="realizado" required>
                                    <option value="S" <?php echo $realizado == 'S' ? 'selected' : ''; ?>>Sim</option>
                                    <option value="N" <?php echo $realizado == 'N' ? 'selected' : ''; ?>>Não</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <label for="horario">Horário</label>
                                <input type="time" class="form-control" id="horario" name="horario" value="<?php echo $horario; ?>" required>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="dt_agenda">Data do Agendamento</label>
                                <input type="date" class="form-control" id="dt_agenda" name="dt_agenda" value="<?php echo $dt_agenda; ?>" required>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="preco_final_agendamento">Preço Final</label>
                                <input type="number" step="0.01" class="form-control" id="preco_final_agendamento" name="preco_final_agendamento" value="<?php echo $preco_final_agendamento; ?>" required>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <button type="submit" class="form-control" id="save" name="save">Salvar</button>
                            </div>
                        </div>
                    </form>
                    <form method="get" action="consulta_agendamento.php">
                        <button type="submit" class="form-control">Consultar Agendamento</button>
                    </form>
                    <form method="get" action="tela_funcionario.php">
                        <button type="submit" class="form-control">Voltar</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
