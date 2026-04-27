# Explorador Cifra Club
Experimentações com a API do Cifra Clube para explorar as cifras disponíveis em busca de coisas como ciifras fáceis.

## Instalação
```bash
composer install
```

## CLI: músicas fáceis por artista
```bash
php cifra-cli.php buscar-musicas-faceis [slug_artista] [numero_maximo_acordes]
```

### Exemplo
```bash
php cifra-cli.php buscar-musicas-faceis padre-marcelo-rossi 5
```

## Interface de busca (web)
Inicie um servidor local do PHP na raiz do projeto:

```bash
php -S 127.0.0.1:8000
```

Depois, abra no navegador:

- http://127.0.0.1:8000/busca.php

Na página você informa o slug do artista e o número máximo de acordes para listar as músicas mais simples.

# Sobre Decisões Técnicas

## Todas as classes no mesmo Namespace
Como esta aplicação é muito simples e tem fins didáticos, optei por manter todas as classes no mesmo namespace.
Entendo que isso traz uma sensação de que o software é menos complicado.
Colocar subdiretórios também me oribigaria a nomear conceitos de forma mais clara, por exemplo, ao agrupar Musica, Artista e Acorde, que nome seria melhor? Modelos? Entidades? ValueObjects? DataTransfer?
Para evitar nomear um grupo de classes sobre um conceito, resolvi manter mais simples nessa primeira versão.

## Código em Inglês ou Português?
O domínio da língua inglesa, por mais que seja desejável para um programador não é realidade no Brasil
e este código tem a intenção de apoiar um número maior de pessoas no desenvolvimento de software guiado a testes.
Optei por manter em inglês unicamente nomes de Padrões de Projeto (Design Patterns) altamente consolidados como "Factory" e "Proxy".
