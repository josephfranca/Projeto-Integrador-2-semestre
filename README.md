Sénate Couture - Sistema de E-Commerce e Gestão

O Sénate Couture é um sistema de e-commerce que combina uma vitrine de moda com um painel administrativo completo para gerenciamento de estoque, produtos, categorias e funcionários.

1. Vitrine Pública (index.html)

    Página inicial elegante com apresentação da marca.

    Destaques de categorias (Feminino e Masculino).

    Botão de acesso rápido para a administração.

2. Controle de Acesso (login.php)

    Tela de autenticação protegida para colaboradores.

    Validação de credenciais de e-mail e senha diretamente no banco de dados.

    Início de sessão seguro via PHP Session.

3. Painel Administrativo (acesso.html)

    Painel centralizador com atalhos funcionais e intuitivos para os módulos do sistema.

4. Gerenciamento de Produtos (adcionarProdutos.php)

    Criação, edição e exclusão (CRUD) de produtos em tempo real usando JavaScript assíncrono (Fetch API).

    Upload de imagens armazenadas de forma segura diretamente no banco de dados como dados binários (BLOB).

5. Gestão de Categorias (categoria.php)

    Painel para criar e excluir categorias.

    Exibição de um contador relacional de quantos produtos pertencem a cada categoria.

6. Auditoria de Estoque (estoque.php)

    Página que lê de forma dinâmica todas as tabelas do SQLite.

    Permite visualizar e editar registros diretamente em formato de tabela.

7. Cadastro de Funcionários (criaUsuario.php)

    Formulário para cadastrar novos colaboradores no sistema com definição de função (ex: Gerente, Vendedor).

Estrutura do Projeto
```text
├── index.html                  # Página pública da loja (Vitrine)
├── database/
│   └── DB_PI2.db               # Arquivo do banco de dados SQLite
├── public/
│   ├── acesso.html             # Atalhos do painel administrativo
│   ├── conexao/conexao.php     # Conexão centralizada via PDO
│   ├── Login_PI2/login.php     # Autenticação de usuários
│   ├── estoque/                # Controle de estoque (estoque.php)
│   ├── adcProdutos/            # CRUD de produtos 
│   ├── categorias/             # Gestão de categorias
│   └── pagCriarUsuario/        # Cadastro de novos colaboradores
└── assets/                     # Arquivos estáticos (CSS e JS)
```
Como Executar o Projeto

Siga os passos abaixo para rodar a aplicação no seu computador:
Passo 1: Pré-requisitos

Certifique-se de ter instalado em sua máquina:

    PHP (versão 8.0 ou superior).

Passo 2: Ativar o SQLite no PHP

O banco de dados do projeto utiliza SQLite, por isso você precisa habilitar a extensão nas configurações do seu PHP:

    Abra o arquivo de configuração do PHP chamado php.ini.

    Procure pelas seguintes linhas e remova o ponto e vírgula (;) do início delas para ativá-las:
    Ini, TOML

    extension=pdo_sqlite
    extension=sqlite3

    Salve o arquivo.

Passo 3: Iniciar o Servidor Local

    Abra o seu terminal de comandos (cmd, bash ou o terminal do VS Code).

    Navegue até a pasta raiz do projeto:
    Bash

    cd caminho/para/o/seu/projeto

    Inicie o servidor embutido do PHP:
    Bash

    php -S localhost:8080

Passo 4: Acessar a Aplicação

    Para ver a Vitrine Pública (Loja): Abra o navegador e acesse http://localhost:8080/index.html.

    Para acessar a Área Administrativa: Acesse diretamente http://localhost:8080/public/Login_PI2/login.php.
