Sénate Couture - Sistema de Gerenciamento de E-Commerce

Este é um sistema completo de e-commerce e retaguarda administrativa focado no setor de moda, desenvolvido com **PHP Estruturado**, **Banco de Dados SQLite 3** e uma interface interativa com **Vanilla JavaScript** e **CSS3**. 

O projeto contempla desde a vitrine pública para os consumidores finais até um ecossistema administrativo restrito para controle de acessos, gestão física de stock, categorização e cadastro de novos colaboradores.

Tecnologias Utilizadas

    Backend: PHP 8.x (Estruturado com uso rigoroso de PDO e Controle de Sessão).

    Frontend: HTML5, CSS3 (Responsividade com CSS Grid e Flexbox), Vanilla JavaScript (comunicando assincronamente com APIs internas do PHP usando Fetch API).

    Banco de Dados: SQLite 3 (DB_PI2.db) para armazenamento rápido de arquivos relacionais.

    Bibliotecas Visuais: Boxicons (módulo de categorias) e Font Awesome 6.0 (painel de acessos).

    Lógica e Arquitetura do Banco de Dados (DB_PI2.db)

O banco de dados foi estruturado de forma relacional para garantir a integridade das entidades do sistema. Abaixo está a modelagem conceitual utilizada:

Tabelas Principais e Campos Chave:

    Produtos: ID_Produtos, Nome, Preco, ID_Categorias (FK), ID_Cores (FK), ID_Tamanhos (FK), Qtd.

    Categorias: ID_Categorias, Nome.

    Cores: ID_Cores, Nome, Rgb.

    Tamanhos: ID_Tamanhos, Nome.

    Produto_Img: ID_Img, ID_Produtos (FK), Arquivo (BLOB), Img_Principal (Boolean).

    usuarios: id, nome, sobrenome, nascimento, email, senha, sexo, funcao.

    Regras de Acesso e Sessões (login.php)

    Autenticação segura: O arquivo login.php valida o e-mail e a senha fornecidos contra a tabela usuarios.

    Armazenamento em Sessão: Ao validar as credenciais, os dados do funcionário são registrados na superglobal $_SESSION['usuario'].

    Redirecionamento: Após o login bem-sucedido, o usuário é transferido para a central administrativa acesso.html.

    Como Executar o Projeto Localmente

    Ative o SQLite no seu Ambiente PHP:
    Certifique-se de abrir o arquivo php.ini do seu servidor local e verificar se as seguintes linhas não possuem o caractere ; na frente (estão ativas):

    Copie o projeto para o seu diretório web:
Insira a pasta raiz no diretório de leitura do seu servidor local (ex: a pasta htdocs do XAMPP ou a pasta pública do Laragon).

Inicie o Servidor:
Você também pode simplesmente iniciar o servidor integrado do PHP executando o comando abaixo no terminal de comandos dentro do diretório raiz do projeto:

Navegue:

    Abra seu navegador e digite: http://localhost:8080/index.html para ver a vitrine da loja.

    Acesse a retaguarda administrativa através da rota: http://localhost:8080/public/Login_PI2/login.php.
