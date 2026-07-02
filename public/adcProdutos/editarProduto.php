<?php
require_once '../conexao/conexao.php';

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? '';
$preco = $_POST['preco'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$tamanho = $_POST['tamanho'] ?? '';
$cor = $_POST['cor'] ?? '';
$qtd = $_POST['qtd'] ?? 1;
$imagem = $_FILES['imagemProduto'] ?? null;

header('Content-Type: application/json');

if (!$id) {
    echo json_encode(['success' => false, 'erro' => 'ID não informado']);
    exit;
}

// Buscar IDs
$stmt = $conexao->prepare("SELECT ID_Categorias FROM Categorias WHERE Nome = ?");
$stmt->execute([$categoria]);
$idCategoria = $stmt->fetchColumn();

$stmt = $conexao->prepare("SELECT ID_Tamanhos FROM Tamanhos WHERE Nome = ?");
$stmt->execute([$tamanho]);
$idTamanho = $stmt->fetchColumn();

$stmt = $conexao->prepare("SELECT ID_Cores FROM Cores WHERE Nome = ?");
$stmt->execute([$cor]);
$idCor = $stmt->fetchColumn();

// Atualizar produto
$stmt = $conexao->prepare("UPDATE Produtos SET Nome = ?, Preco = ?, ID_Categorias = ?, ID_Cores = ?, ID_Tamanhos = ?, Qtd = ? WHERE ID_Produtos = ?");
$ok = $stmt->execute([$nome, $preco, $idCategoria, $idCor, $idTamanho, $qtd, $id]);

// Atualizar imagem se enviada
if ($ok && $imagem && $imagem['error'] === 0) {
    $dadosImg = file_get_contents($imagem['tmp_name']);
    // Verifica se já existe imagem principal
    $stmtCheck = $conexao->prepare("SELECT ID_Img FROM Produto_Img WHERE ID_Produtos = ? AND Img_Principal = 1");
    $stmtCheck->execute([$id]);
    $idImg = $stmtCheck->fetchColumn();
    if ($idImg) {
        // Atualiza imagem existente
        $stmtImg = $conexao->prepare("UPDATE Produto_Img SET Arquivo = ? WHERE ID_Img = ?");
        $stmtImg->bindParam(1, $dadosImg, PDO::PARAM_LOB);
        $stmtImg->bindParam(2, $idImg, PDO::PARAM_INT);
        $stmtImg->execute();
    } else {
        // Insere nova imagem principal
        $stmtImg = $conexao->prepare("INSERT INTO Produto_Img (ID_Produtos, Img_Principal, Arquivo) VALUES (?, 1, ?)");
        $stmtImg->bindParam(1, $id, PDO::PARAM_INT);
        $stmtImg->bindParam(2, $dadosImg, PDO::PARAM_LOB);
        $stmtImg->execute();
    }
}

echo json_encode(['success' => $ok]);