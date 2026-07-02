<?php
require_once '../conexao/conexao.php';

try {
    $query = $conexao->query("SELECT ID_Categorias as id, Nome as nome FROM Categorias");
    $categorias = $query->fetchAll(PDO::FETCH_ASSOC);

    $queryCores = $conexao->query("SELECT Nome FROM Cores");
    $cores = $queryCores->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Erro ao buscar categorias ou cores: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="../assets/css/adicionarProduto.css">
</head>
<body>
    <div class="link">
        <button class="btn-link"><a class="sem-sublinhado" href="../pag_acessos/acesso.html" style="text-decoration: none; color: inherit;">Voltar para Acessos</a></button>
    </div>
    <div class="container">
        <h2>Gerenciar Loja</h2>
        <form id="formProduto" enctype="multipart/form-data" onsubmit="adicionarItem(event)">
            <label for="categoriaSelect">Categoria:</label>
            <select name="categoria" id="categoriaSelect" required>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= htmlspecialchars($categoria['nome']) ?>">
                        <?= htmlspecialchars($categoria['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <a href="../modulo_categoria/categoria.php"><button type="button">Cadastrar Nova Categoria</button></a>

            <input type="text" name="nome" id="itemNome" placeholder="Nome do item" required>
            <input type="number" name="preco" id="precoProduto" placeholder="Preço" step="0.01" min="0" required/>

            <select name="tamanho" id="tamanho" required>
                <option value="">-- Tamanho --</option>
                <option value="P">P</option>
                <option value="M">M</option>
                <option value="G">G</option>
                <option value="GG">GG</option>
            </select>

            <select name="cor" id="cor" required>
                <option value="">-- Cor --</option>
                <?php foreach ($cores as $cor): ?>
                    <option value="<?= htmlspecialchars($cor) ?>"><?= htmlspecialchars($cor) ?></option>
                <?php endforeach; ?>
            </select>
            <a href="adicionarCor.php"><button type="button">Cadastrar Nova Cor</button></a>

            <input type="number" name="qtd" id="quantidadeProduto" placeholder="Quantidade" min="1" value="1" required>
            <input type="file" name="imagemProduto" id="imagemProduto" accept="image/*" required>
            <button type="submit" id="btnAdicionar">Adicionar Item</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Categoria</th>
                    <th>Tamanho</th>
                    <th>Cor</th>
                    <th>Quantidade</th>
                    <th>Imagem</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody id="listaItens"></tbody>
        </table>
    </div>
    <script>
        let idEditando = null;

        function adicionarItem(event) {
            event.preventDefault();

            const nome = document.getElementById('itemNome').value;
            const preco = document.getElementById('precoProduto').value;
            const categoria = document.getElementById('categoriaSelect').value;
            const tamanho = document.getElementById('tamanho').value;
            const cor = document.getElementById('cor').value;
            const qtd = document.getElementById('quantidadeProduto').value;
            const imagem = document.getElementById('imagemProduto').files[0];

            if (!nome || !preco || !categoria || !tamanho || !cor || !qtd || (!window.idEditando && !imagem)) {
                alert('Preencha todos os campos!');
                return;
            }

            const formData = new FormData();
            formData.append('nome', nome);
            formData.append('preco', preco);
            formData.append('categoria', categoria);
            formData.append('tamanho', tamanho);
            formData.append('cor', cor);
            formData.append('qtd', qtd);

            if (window.idEditando) {
                formData.append('id', window.idEditando);
                if (imagem) {
                    formData.append('imagemProduto', imagem);
                }
                fetch('editarProduto.php', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Produto editado com sucesso!');
                        limparCampos();
                        carregarProdutos();
                        window.idEditando = null;
                        document.getElementById('btnAdicionar').textContent = 'Adicionar Item';
                    } else {
                        alert('Erro ao editar produto!');
                    }
                })
                .catch(() => alert('Erro ao conectar ao servidor!'));
            } else {
                formData.append('imagemProduto', imagem);
                fetch('inserirProdutos.php', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Produto adicionado com sucesso!');
                        limparCampos();
                        carregarProdutos();
                    } else {
                        alert('Erro ao adicionar produto!');
                    }
                })
                .catch(() => alert('Erro ao conectar ao servidor!'));
            }
        }

        function carregarProdutos() {
            fetch('listarProdutos.php')
                .then(r => r.json())
                .then(produtos => {
                    const tbody = document.getElementById('listaItens');
                    tbody.innerHTML = '';
                    produtos.forEach(produto => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${produto.produto}</td>
                            <td>${produto.categoria ?? ''}</td>
                            <td>${produto.tamanho ?? ''}</td>
                            <td>${produto.cor ?? ''}</td>
                            <td>${produto.quantidade}</td>
                            <td>
                                ${produto.id_img ? `<img src="exibir_imagem.php?id=${produto.id_img}" style="max-width:50px;max-height:50px;">` : '-'}
                            </td>
                            <td>
                                <button onclick="editarProduto(${produto.id})">Editar</button>
                                <button onclick="removerProduto(${produto.id})">Excluir</button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                });
        }

        function limparCampos() {
            document.getElementById('itemNome').value = '';
            document.getElementById('precoProduto').value = '';
            document.getElementById('tamanho').value = '';
            document.getElementById('cor').value = '';
            document.getElementById('quantidadeProduto').value = 1;
            document.getElementById('imagemProduto').value = '';
            window.idEditando = null;
            document.getElementById('btnAdicionar').textContent = 'Adicionar Item';
        }

        function editarProduto(id) {
            fetch('buscarProduto.php?id=' + id)
                .then(r => r.json())
                .then(produto => {
                    // Preenche o formulário com os dados do produto
                    document.getElementById('itemNome').value = produto.produto;
                    document.getElementById('precoProduto').value = produto.preco;
                    document.getElementById('categoriaSelect').value = produto.categoria;
                    document.getElementById('tamanho').value = produto.tamanho;
                    document.getElementById('cor').value = produto.cor;
                    document.getElementById('quantidadeProduto').value = produto.quantidade;
                    // Não preenche imagem (não é possível por segurança do navegador)
                    window.idEditando = id;
                    document.getElementById('btnAdicionar').textContent = 'Salvar Alterações';
                });
        }

        function removerProduto(id) {
            if (!confirm('Tem certeza que deseja remover este produto?')) return;
            fetch('removerProduto.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + encodeURIComponent(id)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Produto removido com sucesso!');
                    carregarProdutos();
                } else {
                    alert('Erro ao remover produto!');
                }
            })
            .catch(() => alert('Erro ao conectar ao servidor!'));
        }

        window.onload = carregarProdutos;
    </script>
</body>
</html>