# Portal de Notícias

Este projeto é um sistema simples de portal de notícias desenvolvido em PHP com MySQL, voltado para demonstrar funcionalidades básicas de cadastro, login e publicação de notícias.

## Funcionalidades

- Cadastro de usuários
- Login de usuários
- Publicação e visualização de notícias
- Estrutura organizada em pastas para administração e área pública

## Estrutura do projeto

- `admin/` — área administrativa para gerenciamento
- `classes/` — classes PHP do sistema
- `config/` — configuração do banco de dados e scripts SQL
- `contents/` — arquivos de layout como header e footer
- `private/` — páginas internas acessíveis após autenticação
- `public/` — páginas públicas como login, cadastro e visualização de notícias

## Requisitos

- PHP 7 ou superior
- MySQL / MariaDB
- XAMPP ou servidor similar

## Como executar

1. Clone ou copie o projeto para a pasta do seu servidor local, por exemplo `C:/xampp/htdocs/`.
2. Inicie o Apache e o MySQL no XAMPP.
3. Acesse o projeto pelo navegador em `http://localhost/projetoPortalNoticia/`.
4. O sistema tentará criar automaticamente as tabelas necessárias no banco de dados.

## Configuração do banco

O arquivo `config/config.php` contém as informações de conexão com o banco. Ajuste os valores conforme o seu ambiente, se necessário.

## Observações

Este é um projeto simples, ideal para estudos e aprendizado de PHP, organização de pastas e integração com banco de dados.
