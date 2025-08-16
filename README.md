# Sistema de Cardápio Semanal - Cantina IF
Sistema de gerenciamento e visualização do cardápio semanal para a cantina do Instituto Federal desenvolvido em PHP, MySQL, HTML, CSS (Tailwind CSS) e JavaScript.

**Painel Administrativo:**

- Autenticação de Usuários (Login/Logout)

- Recuperação de Senha

- Adição, Edição e Exclusão de itens do cardápio.

- Visualização do cardápio por dia e turno.

- Filtragem e busca de itens do cardápio.

**Página Pública:**

- Visualização do cardápio semanal completo.

- Navegação por dias da semana (vertical/horizontal responsiva).

- Exibição da data da última atualização do cardápio.

- Tecnologias Utilizadas:

* PHP

* MySQL / MariaDB (via XAMPP)

* HTML5

* CSS3 (com Tailwind CSS)

* JavaScript

* Bootstrap (para painel administrativo e alguns componentes/ícones)

* Lucide Icons (para página pública)

Como Rodar o Projeto Localmente:

1. **Pré-requisitos:**

* XAMPP (Apache, MySQL)

* Git

2. **Configuração do Banco de Dados:**

* Inicie o Apache e o MySQL no XAMPP.

* Acesse o phpMyAdmin (http://localhost/phpmyadmin/).

* Crie um novo banco de dados (ex: \cardapio_if`).`

* Execute os scripts SQL para criar as tabelas \cardapio_semanal` e `usuarios`:`

- Tabela cardapio_semanal
CREATE TABLE cardapio_semanal (
id INT AUTO_INCREMENT PRIMARY KEY,
dia_semana VARCHAR(20) NOT NULL,
turno VARCHAR(20) NOT NULL,
tipo_prato VARCHAR(50) NOT NULL,
descricao TEXT NOT NULL,
observacoes TEXT NULL,
status VARCHAR(10) DEFAULT 'ativo',
created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE
CURRENT_TIMESTAMP
);
-- Tabela usuarios
CREATE TABLE usuarios (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
role VARCHAR(20) DEFAULT 'admin',
created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE cardapio_semanal
ADD COLUMN data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP
ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE usuarios
ADD COLUMN reset_token VARCHAR(255) NULL,
ADD COLUMN reset_token_expires_at DATETIME NULL;

* Adicione um usuário administrador na tabela \usuarios` (lembre-se de gerar o hash da senha!):`

INSERT INTO usuarios (username, password, role) VALUES ('seu_username',
'SEU_HASH_DA_SENHA_AQUI', 'admin');

3. **Configuração do Projeto:**

* Clone este repositório para a pasta \htdocs` do seu XAMPP:`

`git clone https://github.com/TiphaneP/Cardapio_IFPE.git C:/xampp/htdocs/cardapio_if`

* Renomeie a pasta clonada para \cardapio_if` (ou o nome que preferir).`

* Edite o arquivo \cardapio_if/config/database.php` e atualize as credenciais do banco de dados.`

4. **Acesso ao Sistema:**

* **Painel Administrativo:** Acesse \http://localhost/cardapio_admin/index.php` para fazer o login.`

* **Página Pública:** Acesse \http://localhost/cardapio_admin/public/index.html` para ver o cardápio.`


  ## 👩‍💻 Autores / Créditos

- **Típhane Pereira** – Desenvolvedora  

  [![GitHub](https://img.shields.io/badge/GitHub-TiphaneP/-black?logo=github)](https://github.com/TiphaneP/)  
  [![LinkedIn](https://img.shields.io/badge/LinkedIn-tiphane-pereira-blue?logo=linkedin)](https://linkedin.com/in/tiphane-pereira)


- **Prof. Josivaldo França** – Orientador  
  

- **João Vitor** – Desenvolvedor
  
  [![GitHub](https://img.shields.io/badge/GitHub-joaovitorbbs1-black?logo=github)](https://github.com/joaovitorbbs1/)  
  [![LinkedIn](https://img.shields.io/badge/LinkedIn-joaovitorbbs-blue?logo=linkedin)](https://linkedin.com/in/joaovitorbbs/)

  
- **Jadson Ribeiro** – Colaborador (Documentação)  




