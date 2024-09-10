<?php
include '../conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Atualização dos Dados
if (isset($_POST['update'])) {
    // Recupera os dados enviados pelo formulário de atualização
    $id = $_POST['id_tipo_consulta'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];

    // Prepara a consulta SQL para atualizar o tipo de consulta
    $sql = "UPDATE tipo_consulta SET descricao=?, preco=? WHERE id_tipo_consulta=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sdi", $descricao, $preco, $id); // Vincula os parâmetros

    // Executa a consulta e fornece feedback ao usuário
    if ($stmt->execute()) {
        echo "<script>alert('Tipo de consulta atualizado com sucesso'); window.location.href='consulta_tipo_consulta.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar tipo de consulta: " . $stmt->error . "'); window.location.href='consulta_tipo_consulta.php';</script>";
    }

    $stmt->close(); // Fecha a declaração
}

// Exclusão dos Dados
if (isset($_GET['delete'])) {
    // Recupera o ID do tipo de consulta a ser excluído
    $id = $_GET['delete'];

    // Prepara a consulta SQL para deletar o tipo de consulta
    $sql = "DELETE FROM tipo_consulta WHERE id_tipo_consulta=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id); // Vincula o parâmetro

    // Executa a consulta e fornece feedback ao usuário
    if ($stmt->execute()) {
        echo "<script>alert('Tipo de consulta deletado com sucesso'); window.location.href='consulta_tipo_consulta.php';</script>";
    } else {
        echo "<script>alert('Erro ao deletar tipo de consulta: " . $stmt->error . "'); window.location.href='consulta_tipo_consulta.php';</script>";
    }

    $stmt->close(); // Fecha a declaração
}

// Filtragem dos Dados
$search = isset($_GET['search']) ? $_GET['search'] : ''; // Recupera o valor de busca, se existir
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Consulta de Tipos de Consulta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Consulta de Tipos de Consulta</h1>

        <!-- Formulário de Busca -->
        <form class="d-flex mb-4" action="consulta_tipo_consulta.php" method="GET">
            <input class="form-control me-2" type="search" name="search" placeholder="Buscar por descrição" aria-label="Search" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-success" type="submit">Buscar</button>
        </form>

        <!-- Tabela de Tipos de Consulta -->
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Prepara a consulta SQL para selecionar os tipos de consulta filtrados pela descrição
                $sql = "SELECT * FROM tipo_consulta WHERE descricao LIKE ? ORDER BY descricao ASC";
                $stmt = $mysqli->prepare($sql);
                $searchParam = "%$search%"; // Prepara o parâmetro de busca com caracteres curinga
                $stmt->bind_param("s", $searchParam); // Vincula o parâmetro
                $stmt->execute();
                $result = $stmt->get_result(); // Obtém o resultado da consulta

                // Verifica se há resultados e exibe-os
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["id_tipo_consulta"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["descricao"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["preco"]) . "</td>";
                        echo "<td>
                                <!-- Botão para editar -->
                                <button class='btn btn-primary btn-sm' onclick='editTipoConsulta(" . json_encode($row) . ")'>Editar</button>
                                <!-- Formulário para deletar -->
                                <form style='display:inline;' method='get' action='consulta_tipo_consulta.php'>
                                    <input type='hidden' name='delete' value='" . $row["id_tipo_consulta"] . "'>
                                    <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja deletar este tipo de consulta?\")'>Deletar</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Nenhum tipo de consulta encontrado</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Botões de Ação -->
        <div class="mb-4">
            <a href="tipo_consulta.php" class="btn btn-primary">Adicionar Novo Tipo de Consulta</a>
            <a href="tela_funcionario.php" class="btn btn-secondary">Voltar para Tela de Funcionário</a>
        </div>

        <!-- Formulário de Edição -->
        <div id="editForm" class="mt-4" style="display:none;">
            <h2>Editar Tipo de Consulta</h2>
            <form method="post" action="consulta_tipo_consulta.php">
                <input type="hidden" id="edit_id_tipo_consulta" name="id_tipo_consulta">
                <div class="form-group mb-3">
                    <label for="edit_descricao">Descrição</label>
                    <input type="text" class="form-control" id="edit_descricao" name="descricao">
                </div>
                <div class="form-group mb-3">
                    <label for="edit_preco">Preço</label>
                    <input type="number" class="form-control" id="edit_preco" name="preco" step="0.01">
                </div>
                <button type="submit" name="update" class="btn btn-success">Salvar</button>
            </form>
        </div>
    </div>
    <script>
        // Função para preencher e mostrar o formulário de edição com os dados do tipo de consulta
        function editTipoConsulta(tipoConsulta) {
            document.getElementById('edit_id_tipo_consulta').value = tipoConsulta.id_tipo_consulta;
            document.getElementById('edit_descricao').value = tipoConsulta.descricao;
            document.getElementById('edit_preco').value = tipoConsulta.preco;
            document.getElementById('editForm').style.display = 'block'; // Exibe o formulário de edição
            window.scrollTo(0, document.getElementById('editForm').offsetTop); // Rola a página para o formulário
        }
    </script>
</body>
</html>
