<?php
include '../conexao.php';

session_start();
if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 'Funcionario') {
    header("Location: login2.php");
    exit();
}

$admin = $_SESSION['admin'];
$id_funcionario = $_SESSION['id_secretaria']; // ID do funcionário salvo na sessão

// Buscar o nome do funcionário
$sql = "SELECT nome FROM secretaria WHERE id_secretaria = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_funcionario);
$stmt->execute();
$stmt->bind_result($nome_funcionario);
$stmt->fetch();
$stmt->close();

// Contar agendamentos do dia
$sql_agendamentos = "SELECT COUNT(*) FROM agendamento ";
$result_agendamentos = $mysqli->query($sql_agendamentos);
$num_agendamentos = $result_agendamentos->fetch_row()[0];

// Contar pacientes cadastrados
$sql_pacientes = "SELECT COUNT(*) FROM paciente";
$result_pacientes = $mysqli->query($sql_pacientes);
$num_pacientes = $result_pacientes->fetch_row()[0];

// Contar tarefas pendentes (simulação, ajuste conforme necessário)
$sql_tarefas = "SELECT COUNT(*) FROM agendamento WHERE realizado = 'N'";
$result_tarefas = $mysqli->query($sql_tarefas);
$num_tarefas = $result_tarefas->fetch_row()[0];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela Funcionario - Bucal Saúde</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../dentista/style.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <div class="me-3 ms-3">
                    <a href="../index.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="40" fill="currentColor" class="bi bi-reply-fill" viewBox="0 0 16 16">
                            <path d="M5.921 11.9 1.353 8.62a.719.719 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.719V6c1.5 0 6 0 7 8-2.5-4.5-7-4-7-4v1.281c0 .56-.606.898-1.079.62z" />
                        </svg>
                    </a>
                    <a class="navbar-brand" href="../index.php">
                        <i class="fa-solid fa-tooth"></i> Bucal Saúde
                    </a>
                </div>

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="agendamento_form.php"><strong>Agendamento</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tipo_consulta.php"><strong>Consultas</strong></a>
                    </li>
                    <li class="nav-item">
                        <?php if ($admin == 'S') : ?>
                            <a class="nav-link text-white" href="cadastro_login.php">Administrador</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro_paciente.php">Paciente</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <section class="container mt-5">
        <h2 class="text-center">Bem-vindo, <?php echo htmlspecialchars($nome_funcionario); ?>!</h2>
        <br><br><br><br><br>
        <!-- Dashboard Overview -->
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Agendamentos do Dia</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $num_agendamentos; ?> agendamentos</h5>
                        <p class="card-text">Visualize todos os agendamentos de hoje.</p>
                        <a href="consulta_agendamento.php" class="btn btn-light">Ver Agendamentos</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Pacientes</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $num_pacientes; ?> Pacientes</h5>
                        <p class="card-text">Acesse os dados dos pacientes cadastrados.</p>
                        <a href="consulta_pacientes.php" class="btn btn-light">Ver Pacientes</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Tarefas Pendentes</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $num_tarefas; ?> Tarefas</h5>
                        <p class="card-text">Veja as tarefas pendentes para hoje.</p>
                        <a href="consulta_agendamento.php" class="btn btn-light">Ver Tarefas</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>
