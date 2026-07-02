<?php
require_once '../conexao/conexao.php';

$nome = $_POST['nome'] ?? '';
$preco = $_POST['preco'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$tamanho = $_POST['tamanho'] ?? '';
$cor = $_POST['cor'] ?? '';
$qtd = $_POST['qtd'] ?? 1;
$imagem = $_FILES['imagemProduto'] ?? null;

header('Content-Type: application/json');

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

// Inserir produto
$stmt = $conexao->prepare("INSERT INTO Produtos (Nome, Preco, ID_Categorias, ID_Cores, ID_Tamanhos, Qtd) VALUES (?, ?, ?, ?, ?, ?)");
$ok = $stmt->execute([$nome, $preco, $idCategoria, $idCor, $idTamanho, $qtd]);

if ($ok) {
    $idProduto = $conexao->lastInsertId();

    // Se houver imagem, salva na Produto_Img
    if ($imagem && $imagem['error'] === 0) {
        $dadosImg = file_get_contents($imagem['tmp_name']);
        $stmtImg = $conexao->prepare("INSERT INTO Produto_Img (ID_Produtos, Img_Principal, Arquivo) VALUES (?, 1, ?)");
        $stmtImg->bindParam(1, $idProduto, PDO::PARAM_INT);
        $stmtImg->bindParam(2, $dadosImg, PDO::PARAM_LOB);
        $stmtImg->execute();
    }
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}