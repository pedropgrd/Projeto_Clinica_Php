<?php
include '../conexao.php';  // Inclui o arquivo de conexão com o banco de dados

// Exclusão dos Dados
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];  // Obtém o ID do agendamento a ser excluído

    // SQL para excluir o agendamento com o ID especificado
    $sql = "DELETE FROM agendamento WHERE id_agenda=$id";
    if ($mysqli->query($sql) === TRUE) {
        // Se a exclusão for bem-sucedida, exibe uma mensagem e redireciona para a página de consulta
        echo "<script>alert('Agendamento deletado com sucesso'); window.location.href='consulta_agendamento.php';</script>";
    } else {
        // Se houver um erro, exibe uma mensagem de erro e redireciona para a página de consulta
        echo "<script>alert('Erro ao deletar agendamento: " . $mysqli->error . "'); window.location.href='consulta_agendamento.php';</script>";
    }
}

// Filtragem dos Dados
$search = isset($_GET['search']) ? $_GET['search'] : '';  // Obtém o termo de busca, se houver
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Agendamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Inclui o CSS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> <!-- Inclui o JavaScript do Bootstrap -->
</head>
<body>
    <div class="container mt-5">  <!-- Container principal com margem superior -->
        <h1>Consulta de Agendamentos</h1>

        <!-- Formulário de Busca -->
        <form class="d-flex mb-4" action="consulta_agendamento.php" method="GET">
            <input class="form-control me-2" type="search" name="search" placeholder="Buscar" aria-label="Search" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-success" type="submit">Buscar</button>
        </form>

        <!-- Tabela para exibir os agendamentos -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Dentista</th>
                    <th>Consulta</th>
                    <th>Descrição</th>
                    <th>Realizado</th>
                    <th>Horário</th>
                    <th>Data</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // SQL para buscar os agendamentos com base na busca
                $sql = "SELECT 
                            agendamento.id_agenda,
                            paciente.nome AS nome_paciente,
                            dentista.nome AS nome_dentista,
                            tipo_consulta.descricao AS descricao_consulta,
                            agendamento.descricao,
                            agendamento.realizado,
                            agendamento.horario,
                            agendamento.dt_agenda,
                            agendamento.preco_final_agendamento
                        FROM agendamento
                        JOIN paciente ON agendamento.id_paciente = paciente.id_paciente
                        JOIN dentista ON agendamento.id_dentista = dentista.id_dentista
                        JOIN tipo_consulta ON agendamento.id_tipo_consulta = tipo_consulta.id_tipo_consulta
                        WHERE paciente.nome LIKE '%$search%'  -- Filtra os resultados com base no nome do paciente
                        ORDER BY agendamento.dt_agenda DESC";  // Ordena os resultados pela data do agendamento em ordem decrescente

                $result = $mysqli->query($sql);  // Executa a query

                if ($result->num_rows > 0) {
                    // Se houver resultados, exibe cada linha na tabela
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id_agenda"] . "</td>";
                        echo "<td>" . $row["nome_paciente"] . "</td>";
                        echo "<td>" . $row["nome_dentista"] . "</td>";
                        echo "<td>" . $row["descricao_consulta"] . "</td>";
                        echo "<td>" . $row["descricao"] . "</td>";
                        echo "<td>" . ($row["realizado"] == 'S' ? 'Sim' : 'Não') . "</td>";
                        echo "<td>" . $row["horario"] . "</td>";
                        echo "<td>" . $row["dt_agenda"] . "</td>";
                        echo "<td>R$ " . number_format($row["preco_final_agendamento"], 2, ',', '.') . "</td>";
                        echo "<td>
                                <a href='agendamento_form.php?id=" . $row["id_agenda"] . "' class='btn btn-primary'>Editar</a>
                                <a href='consulta_agendamento.php?delete=" . $row["id_agenda"] . "' class='btn btn-danger' onclick='return confirm(\"Tem certeza que deseja deletar este agendamento?\")'>Excluir</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    // Se não houver resultados, exibe uma linha indicando que nenhum agendamento foi encontrado
                    echo "<tr><td colspan='10'>Nenhum agendamento encontrado</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Botões para criar um novo agendamento ou voltar -->
        <div class="mt-3">
            <a href="agendamento_form.php" class="btn btn-primary">Novo Agendamento</a>
            <a href="tela_funcionario.php" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
</body>
</html>
