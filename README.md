# Estudando CI/CD com PHP

Projeto didatico para aprender **Continuous Integration e Continuous Delivery** com PHP usando GitHub Actions e Render.

---

## O que e CI/CD?

| Sigla | Nome | O que faz |
|---|---|---|
| **CI** | Continuous Integration | Testa o codigo automaticamente a cada `git push` |
| **CD** | Continuous Delivery | Deixa o build aprovado pronto para publicar |
| **CD** | Continuous Deployment | Publica automaticamente no servidor |

**Sem CI/CD:** voce testa na mao, copia arquivos via FTP, torce pra funcionar.

**Com CI/CD:** voce faz `git push` e o pipeline faz tudo sozinho.

---

## Fluxo completo CI + CD

```
voce edita o codigo
        |
        v
git push origin main
        |
        v
GitHub Actions dispara
        |
   [CI] testes com PHPUnit    <- se falhar, para aqui
   [CI] analise com PHPStan   <- se falhar, para aqui
        |
        v
   [CD] deploy no Render      <- so chega aqui se CI passou
        |
        v
app online em https://estudant-ci-cd.onrender.com
```

---

## Estrutura do Projeto

```
estudant-ci-cd/
├── public/
│   └── index.php                <- ponto de entrada da aplicacao
├── src/
│   └── Calculadora.php          <- codigo da aplicacao
├── tests/
│   └── CalculadoraTest.php      <- testes automatizados (PHPUnit)
├── .github/
│   └── workflows/
│       └── ci.yml               <- pipeline CI + CD (GitHub Actions)
├── Dockerfile                   <- container para deploy no Render
├── render.yaml                  <- configuracao do Render
├── composer.json                <- dependencias PHP
├── phpunit.xml                  <- configuracao do PHPUnit
├── .gitignore
└── README.md
```

---

## Como rodar localmente

### Pre-requisitos
- PHP 8.3+
- Composer instalado

### Instalando dependencias

```bash
composer install
```

### Rodando os testes

```bash
./vendor/bin/phpunit --testdox
```

Saida esperada:
```
Calculadora
 [x] Soma dois numeros
 [x] Subtrai dois numeros
 [x] Multiplica dois numeros
 [x] Divide dois numeros
 [x] Divisao por zero lanca excecao

OK (5 tests, 6 assertions)
```

### Rodando a analise estatica

```bash
./vendor/bin/phpstan analyse src --level=8
```

### Rodando a aplicacao localmente

```bash
php -S localhost:8080 -t public
```

Acesse: `http://localhost:8080`

---

## O Pipeline (ci.yml) explicado

```yaml
name: CI - PHP

on:
  push:                     # dispara no git push
    branches: [ "main" ]
  pull_request:             # dispara ao abrir Pull Request
    branches: [ "main" ]

jobs:
  testes:                   # Job 1 - CI
    runs-on: ubuntu-latest
    steps:
      - checkout            # baixa o codigo
      - setup PHP 8.3       # instala o PHP
      - composer install    # instala dependencias
      - phpunit             # roda os testes
      - phpstan             # analise estatica

  deploy:                   # Job 2 - CD
    needs: testes           # so roda se "testes" passou
    if: push na main        # so em push direto na main
    steps:
      - dispara deploy      # chama a API do Render via curl
```

---

## Conceitos do Git

| Comando | O que faz |
|---|---|
| `git init` | Transforma a pasta em repositorio Git |
| `git add .` | Adiciona todos os arquivos na staging area |
| `git commit -m "msg"` | Salva um snapshot do codigo com mensagem |
| `git push origin main` | Envia commits locais para o GitHub |
| `git pull` | Baixa e aplica commits do GitHub na maquina |
| `git checkout -b nome` | Cria e entra numa branch nova |
| `git branch -d nome` | Apaga uma branch local |
| `git push origin --delete nome` | Apaga uma branch remota |
| `git status` | Mostra o que mudou e o que esta na staging area |
| `git log --oneline` | Historico resumido de commits |

### As 3 areas do Git

```
Working Directory     Staging Area       Repositorio
(seus arquivos)       (sala de espera)   (historico)

edita arquivos  ->  git add .  ->  git commit -m "..."
```

### O que e uma branch?

```
main  o---o---o---o          <- codigo oficial
               \
                o---o---o    <- feature/nova-funcao (isolada)
```

Branches permitem trabalhar em funcionalidades novas sem afetar o codigo principal. Apos terminar, abre-se um **Pull Request** para unir com a `main`.

---

## Conceitos do GitHub Actions

| Termo | Significado |
|---|---|
| **Workflow** | O arquivo `.yml` inteiro |
| **Trigger** | Evento que dispara o workflow (`push`, `pull_request`) |
| **Job** | Conjunto de steps numa mesma maquina |
| **Step** | Um comando ou action dentro do job |
| **Runner** | Maquina virtual onde o job roda (`ubuntu-latest`) |
| **Action** | Bloco reutilizavel (`uses: actions/checkout@v4`) |
| **Secret** | Variavel sensivel configurada no GitHub, nunca no codigo |
| **needs** | Dependencia entre jobs |

---

## Configurando os Secrets no GitHub

Para o job de deploy funcionar pelo pipeline, adicione no GitHub:

```
GitHub > Settings > Secrets and variables > Actions

RENDER_SERVICE_ID  <- ID do servico no Render (Settings do servico)
RENDER_API_KEY     <- API Key gerada em Account Settings > API Keys
```

---

## Como o CI bloqueia codigo com erro

1. Crie uma branch: `git checkout -b feature/teste`
2. Quebre um teste de proposito
3. Faca push e abra um Pull Request
4. O CI vai falhar e o GitHub vai mostrar:
   ```
   x All checks have failed
     Merging is blocked until checks pass
   ```
5. Corrija o erro, faca outro push no mesmo PR
6. O CI passa e o merge e liberado

---

## Ferramentas utilizadas

- [PHP 8.3](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [PHPUnit 11](https://phpunit.de/)
- [PHPStan](https://phpstan.org/)
- [GitHub Actions](https://docs.github.com/en/actions)
- [Render](https://render.com/)
- [Docker](https://www.docker.com/)

---

## Badge de Status do CI

![CI](https://github.com/WanderAmaral/estudant-ci-cd/actions/workflows/ci.yml/badge.svg)
