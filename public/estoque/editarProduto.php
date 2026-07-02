<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Gerenciamento de Estoque</title>
<style>
    
    * {
        box-sizing: border-box;
    }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f7fa;
        margin: 0;
        padding: 20px;
        color: #333;
    }
    h2 {
        border-bottom: 3px solid black;
        padding-bottom: 6px;
        margin-bottom: 16px;
        font-weight: 700;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 40px;
        background: #fff;
        box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
        border-radius: 8px;
        overflow: hidden;
    }
    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: black;
        color: white;
        font-weight: 600;
        user-select: none;
    }
    tr:hover {
        background-color: #f1faff;
    }
    input[type="text"],
    input[type="number"] {
        width: 90%;
        padding: 6px 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    input[type="text"]:focus,
    input[type="number"]:focus {
        outline: none;
        border-color: #007BFF;
        box-shadow: 0 0 5px rgba(0,123,255,0.5);
    }
    button {
        background-color: #CCC;
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.25s ease;
        font-size: 14px;
    }
    button:hover {
        background-color: #0056b3;
    }
    form {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }
    /* Responsividade simples */
    @media (max-width: 768px) {
        th, td {
            font-size: 13px;
            padding: 8px 10px;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
        }
        form {
            flex-direction: column;
            align-items: stretch;
        }
        button {
            width: 100%;
            margin-top: 8px;
        }
    }
</style>
</head>
<body>

<?php
require_once '../conexao/conexao.php';

function atualizarProduto($conexao, $id, $nome, $preco, $categoria, $cor, $tamanho, $qtd) {
    $stmt = $conexao->prepare("UPDATE Produtos 
        SET Nome = ?, Preco = ?, ID_Categorias = ?, ID_Cores = ?, ID_Tamanhos = ?, Qtd = ? 
        WHERE ID_Produtos = ?");
    return $stmt->execute([$nome, $preco, $categoria, $cor, $tamanho, $qtd, $id]);
}

$tabelas = ['Produtos', 'Categorias', 'Cores', 'Tamanhos'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar']) && isset($_POST['tabela'])) {
    $tabela = $_POST['tabela'];
    if ($tabela === 'Produtos') {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $categoria = $_POST['categoria'];
        $cor = $_POST['cor'];
        $tamanho = $_POST['tamanho'];
        $qtd = $_POST['qtd'];

        if (atualizarProduto($conexao, $id, $nome, $preco, $categoria, $cor, $tamanho, $qtd)) {
            echo "<script>alert('Produto atualizado com sucesso!'); window.location.href = 'estoque.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar o produto.');</script>";
        }
    } elseif (in_array($tabela, ['Categorias', 'Cores', 'Tamanhos'])) {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $colunaId = "ID_" . $tabela;

        $stmt = $conexao->prepare("UPDATE $tabela SET Nome = ? WHERE $colunaId = ?");
        if ($stmt->execute([$nome, $id])) {
            echo "<script>alert('$tabela atualizada com sucesso!'); window.location.href = 'estoque.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar $tabela.');</script>";
        }
    // } elseif ($tabela === 'Usuarios') {
    //     $id = $_POST['id'];
    //     $nome = $_POST['nome'];
    //     $email = $_POST['email'];
    //     $senha = $_POST['senha'];
    //     // $nivel = $_POST['nivel'];

        $stmt = $conexao->prepare("UPDATE Usuarios SET Nome = ?, Email = ?, Senha = ?, Nivel = ? WHERE ID_Usuarios = ?");
        if ($stmt->execute([$nome, $email, $senha, $id])) {
            echo "<script>alert('Usuário atualizado com sucesso!'); window.location.href = 'estoque.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar Usuário.');</script>";
        }
    }
}

foreach ($tabelas as $tabela) {
    echo "<h2>$tabela</h2>";

    $stmt = $conexao->query("SELECT * FROM $tabela");
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($registros) === 0) {
        echo "<p>Nenhum registro encontrado em $tabela.</p>";
        continue;
    }

    echo "<table>";
    echo "<tr>";
    foreach (array_keys($registros[0]) as $coluna) {
        echo "<th>$coluna</th>";
    }
    echo "<th>Ações</th></tr>";

    foreach ($registros as $registro) {
        echo "<tr>";
        foreach ($registro as $valor) {
            echo "<td>" . htmlspecialchars($valor) . "</td>";
        }

        echo "<td><form method='POST'>";
        echo "<input type='hidden' name='tabela' value='$tabela'>";
        echo "<input type='hidden' name='id' value='" . $registro[array_key_first($registro)] . "'>";

        if ($tabela === 'Produtos') {
            echo "<input type='text' name='nome' value='" . htmlspecialchars($registro['Nome']) . "' required placeholder='Nome'>";
            echo "<input type='number' step='0.01' name='preco' value='" . $registro['Preco'] . "' required placeholder='Preço'>";
            echo "<input type='hidden' name='categoria' value='" . $registro['ID_Categorias'] . "'>";
            echo "<input type='hidden' name='cor' value='" . $registro['ID_Cores'] . "'>";
            echo "<input type='hidden' name='tamanho' value='" . $registro['ID_Tamanhos'] . "'>";
            echo "<input type='number' name='qtd' value='" . $registro['Qtd'] . "' required placeholder='Quantidade'>";
        } elseif (in_array($tabela, ['Categorias', 'Cores', 'Tamanhos'])) {
            echo "<input type='text' name='nome' value='" . htmlspecialchars($registro['Nome']) . "' required placeholder='Nome'>";
        } 

        echo "<button type='submit' name='editar'>Salvar</button>";
        echo "</form></td>";
        echo "</tr>";
    }
    echo "</table><br>";
}
?>

</body>
</html>
