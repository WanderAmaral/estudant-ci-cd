# Estudando CI/CD com PHP

Projeto didatico para aprender **Continuous Integration e Continuous Delivery** com PHP usando GitHub Actions.

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

## Fluxo do Pipeline

```
git push origin main
        |
        v
  GitHub Actions dispara
        |
        v
  Maquina Ubuntu limpa e criada
        |
        v
  [1] Checkout do codigo
  [2] PHP 8.3 instalado
  [3] composer install
  [4] phpunit (testes)
  [5] phpstan (analise estatica)
        |
        v
  Verde = sucesso | Vermelho = falha (bloqueia o merge)
```

---

## Estrutura do Projeto

```
estudant-ci-cd/
├── src/
│   └── Calculadora.php          <- codigo da aplicacao
├── tests/
│   └── CalculadoraTest.php      <- testes automatizados (PHPUnit)
├── .github/
│   └── workflows/
│       └── ci.yml               <- pipeline do GitHub Actions
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

---

## O Pipeline (ci.yml) explicado

```yaml
name: CI - PHP          # nome que aparece no GitHub

on:
  push:                 # dispara quando voce faz git push
    branches: [ "main" ]
  pull_request:         # dispara quando abre um Pull Request
    branches: [ "main" ]

jobs:
  testes:
    runs-on: ubuntu-latest    # maquina virtual Ubuntu

    steps:
      - uses: actions/checkout@v4           # baixa seu codigo
      - uses: shivammathur/setup-php@v2     # instala o PHP
      - run: composer install               # instala dependencias
      - run: ./vendor/bin/phpunit           # roda os testes
      - run: ./vendor/bin/phpstan analyse   # analise estatica
```

---

## Conceitos do GitHub Actions

| Termo | Significado |
|---|---|
| **Workflow** | O arquivo `.yml` inteiro — define todo o processo |
| **Trigger** | O evento que dispara o workflow (`push`, `pull_request`) |
| **Job** | Um conjunto de steps que rodam numa mesma maquina |
| **Step** | Um comando ou action dentro de um job |
| **Runner** | A maquina virtual onde o job roda (`ubuntu-latest`) |
| **Action** | Um bloco reutilizavel (`uses: actions/checkout@v4`) |
| **Secret** | Variavel sensiivel configurada no GitHub, nunca no codigo |

---

## Como testar o pipeline quebrando de proposito

1. Abra [tests/CalculadoraTest.php](tests/CalculadoraTest.php)
2. Mude `assertSame(5, $resultado)` para `assertSame(99, $resultado)`
3. Faca `git push`
4. Va na aba **Actions** do GitHub e veja o pipeline falhar em vermelho
5. Reverta a mudanca, faca outro push e veja ficar verde

Isso e exatamente o que o CI faz na vida real: **impede que codigo quebrado chegue na branch principal.**

---

## Proximos passos

- [ ] Adicionar coverage de testes (XDEBUG + `--coverage-html`)
- [ ] Adicionar PHP CS Fixer para padronizar o estilo de codigo
- [ ] Configurar um job de **deploy automatico** via SSH
- [ ] Testar com **matrix** (rodar em PHP 8.2 e 8.3 ao mesmo tempo)
- [ ] Adicionar badge de status do CI no README

---

## Badge de Status

Apos criar o repositorio no GitHub, adicione esta badge no topo do README (substitua `SEU_USUARIO` e `SEU_REPOSITORIO`):

```markdown
![CI](https://github.com/SEU_USUARIO/SEU_REPOSITORIO/actions/workflows/ci.yml/badge.svg)
```

Ela fica assim: mostra verde quando o pipeline passa e vermelho quando falha.

---

## Ferramentas utilizadas

- [PHP 8.3](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [PHPUnit 11](https://phpunit.de/)
- [PHPStan](https://phpstan.org/)
- [GitHub Actions](https://docs.github.com/en/actions)
