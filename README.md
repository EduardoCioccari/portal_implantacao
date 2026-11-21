# Portal do Cliente – Gestão de Implantação

Projeto Tecnológico em Desenvolvimento de Sistemas – ULBRA (Análise e Desenvolvimento de Sistemas).

**Sistema publicado:** https://portal-implantacao.infinityfree.me

## Descrição

O Portal de Implantação é um sistema que facilita a comunicação entre analista e cliente durante a implantação de um software. Ele registra oficialmente os marcos/itens a serem atingidos e documenta a realização de cada um, com as observações do cliente e as validações do analista. Assim, ambos têm uma visão macro dos prazos, desafios e validações de cada item. Ao final, quando todos os itens são concluídos, o sistema gera um termo de conclusão em PDF, que é assinado pelas duas partes.

### Perfis de acesso

| Perfil | O que pode fazer |
|---|---|
| **Analista** | Cadastra clientes e os itens da implantação, valida ou devolve os itens executados, cadastra a imagem da sua assinatura e baixa o termo assinado pelo cliente. |
| **Cliente** | Acompanha os itens da sua implantação, marca itens como executados (com observação), baixa o termo de conclusão e envia o termo assinado. |

### Fluxo do item

```
Pendente ──(cliente marca como executado)──> Aguardando validação
Aguardando validação ──(analista valida)──> Concluído
Aguardando validação ──(analista devolve)──> Pendente
```

Quando **todos** os itens de um cliente ficam concluídos, o sistema gera automaticamente o **Termo de Conclusão em PDF**, com a assinatura do analista.

## Tecnologias

| Tecnologia | Versão | Uso |
|---|---|---|
| PHP | 8.3 (local) / 8.4 (produção) | Back-end, MVC sem framework |
| MySQL / MariaDB | 10.4+ | Banco de dados |
| PDO | nativo do PHP | Acesso ao banco com prepared statements |
| Composer | 2.x | Dependências e autoload PSR-4 |
| DomPDF | 3.1 | Geração do PDF de conclusão |
| Bootstrap | 5.3 (CDN) | Interface |
| Apache (mod_rewrite) | 2.4 | URLs amigáveis via `.htaccess` |

## Instalação e execução local (XAMPP)

1. **Clone o repositório** dentro da pasta `htdocs` do XAMPP:
   ```bash
   git clone <link-do-repositorio> projeto_implantacao
   ```
2. **Instale as dependências** (a pasta `vendor/` não está no repositório):
   ```bash
   cd projeto_implantacao
   composer install
   ```
3. **Crie o banco de dados:** no phpMyAdmin, importe o arquivo `database/portal_implantacao.sql` (ele cria o banco `portal_implantacao`, as tabelas e os dados de teste).
4. **Configure a conexão:** copie `config/database.exemplo.php` para `config/database.php` e ajuste usuário e senha do MySQL, se necessário.
5. **Acesse:** inicie o Apache e o MySQL no XAMPP e abra `http://localhost/projeto_implantacao/`.

> O arquivo `config/database.php` está no `.gitignore` e nunca é versionado, pois contém credenciais.

## Estrutura do projeto (MVC)

```
projeto_implantacao/
├── .htaccess              # Redireciona todas as requisições para public/
├── app/
│   ├── Controllers/       # Recebem a requisição, validam e chamam os Models
│   ├── Core/              # Classes de apoio: Database, Auth e Pdf
│   ├── Models/            # Consultas ao banco (SQL com PDO)
│   └── Views/             # Telas HTML, separadas por entidade
├── config/                # Configuração do banco (database.exemplo.php)
├── database/              # Script SQL do banco
├── public/
│   ├── .htaccess          # Envia tudo para o index.php
│   └── index.php          # Front controller: ponto de entrada e rotas
├── storage/               # Assinaturas e PDFs (fora da public/, sem acesso direto pela URL)
└── vendor/                # Dependências do Composer (não versionado)
```

## Orientações para análise do código

| Onde olhar | O que está lá |
|---|---|
| `public/index.php` | Todas as rotas do sistema (`switch` com método + caminho) |
| `app/Core/Database.php` | Conexão PDO única (Singleton), charset utf8mb4 e modo de exceções |
| `app/Core/Auth.php` | Controle de acesso por perfil (`exigirPerfil`) |
| `app/Controllers/LoginController.php` | Login com `password_verify` e `session_regenerate_id` |
| `app/Controllers/ItemController.php` | Fluxo dos itens: execução, validação, devolução e disparo do PDF |
| `app/Models/Item.php` | Transações (`beginTransaction` / `commit` / `rollBack`) ao mudar status e gravar observação |
| `app/Core/Pdf.php` + `app/Views/pdf/conclusao.php` | Geração do Termo de Conclusão com DomPDF |
| `app/Controllers/DocumentoController.php` | Envio e download dos PDFs, com verificação de permissão |

### Casos de uso × código

| UC | Função | Controller |
|---|---|---|
| UC01 | Login / logout | `LoginController` |
| UC02 | Cadastrar cliente | `ClienteController` |
| UC03 | Enviar imagem da assinatura | `AssinaturaController` |
| UC04, UC05 | Cadastrar e listar itens | `ItemController::novo`, `salvar`, `index`, `meusItens` |
| UC06 | Marcar item como executado | `ItemController::executar` |
| UC07 | Listar itens aguardando validação | `ItemController::validacoes` |
| UC08, UC09 | Validar ou devolver item | `ItemController::avaliar` |
| UC10 | Gerar PDF de conclusão | `Pdf::gerarConclusao` |
| UC11 | Baixar termo e enviar termo assinado | `DocumentoController::baixarCliente`, `enviarAssinado` |
| UC12 | Baixar termo assinado | `DocumentoController::baixarAnalista` |

### Segurança aplicada

- **Senhas:** `password_hash` / `password_verify` (bcrypt), nunca em texto puro.
- **SQL Injection:** todas as consultas usam prepared statements do PDO.
- **XSS:** todo dado exibido passa por `htmlspecialchars`.
- **Acesso indevido (IDOR):** cada consulta confere se o registro pertence ao usuário logado (ex.: `WHERE id = ? AND analista_id = ?`); registros de outros usuários retornam 404.
- **Sessão:** `session_regenerate_id(true)` após o login.
- **Arquivos:** assinaturas e PDFs ficam em `storage/`, fora da pasta pública; o download passa pelo PHP, que verifica a permissão antes de entregar o arquivo.
