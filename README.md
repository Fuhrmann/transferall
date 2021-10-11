## TransferAll

![Alt text](screenshot1.png "Dashboard")

----

## Avaliação ##

O comando de validação do código foi executado (`jakzal/phpqa`) porém a versão do `phpmd` que se encontra na toolbox é a 2.9.1 e o suporte para o PHP 8.* foi adicionado somente na versão 2.10 ([aqui](https://github.com/phpmd/phpmd/pull/878)). A toolbox do `jakzal` não instala o `phpmd` mais recente pois não tem assinatura gpg.

```
[WARNING]  phpmd 2.10.2: No GPG Signature
[WARNING]  phpmd 2.10.1: No GPG Signature
[WARNING]  phpmd 2.10.0: No GPG Signature
```

Então os erros encontrados ao rodar tal comando estão relacionados a incompatibilidade do `phpmd` com o PHP 8.

----

## Instalação local ##

Execute `make install` para inicializar o sistema e os containers. Caso queira verificar a disponibilidade dos containers a qualquer momento, execute `make ps`.

Estou utilizando o **Caddy** para gerenciar meus projetos sem precisar expor as portas utilizando o docker, então adicionei uma nova regra no arquivo de configuração do **Caddy** e especifiquei como proxy reverso:

```
desafio.local.dev {
  import static
  import tls
  reverse_proxy desafio-nginx:80
}
```

Caso prefira expor as portas, base adicionar no arquivo `.env` do projeto as seguintes linhas:

```
DOCKER_NGINX_PORT=8081
DOCKER_DB_PORT=3306
```

### Usuário de demonstração ###

**Login:** `demo@demo.com.br`\
**Senha:** `123456`

---

## Instalação em produção ##

Certifique-se que no seu arquivo `.env` esteja presente:

```
APP_ENV=production
APP_DEBUG=false
```

Após isso, basta executar `make build` para criar as imagens necessárias. Este comando irá criar duas imagens prontas para produção: uma para o PHP e outra para o NGIX.

As imagens criadas estarão disponíveis com a tag `prod`:

| Repository  | TAG | SIZE |
| ------------- | ------------- | ------------- |
| desafio-nginx  | prod  | 22.9MB
| desafio-php  | prod  | 205MB

## Inspecionando o banco de dados ##

Para se conectar ao banco de dados basta executar na raiz do projeto:

```make connect-db```

que o console do MySQL irá abrir em seu terminal pronto para ser utilizado.

## Testes ##

Para executar os testes basta rodar:

```make run-tests``` ou `docker-compose exec php php artisan test`

## Queue ##

O envio de e-mail (notificações) é realizado através de uma queue. Para acessar o log de forma fácil e visualizar os e-mails enviados em tempo real basta executar:

```make logs queue```



------

## Endpoints

### Listar todas as transações

#### Request

`GET /api/transactions`

    curl -i -H 'Accept: application/json' http://localhost/api/transactions

#### Response

```json
{
    "data": [
        {
            "id": 23,
            "wallet_payer_id": 2,
            "wallet_payee_id": 3,
            "from_name": "Usuário Demonstração",
            "to_name": "Miguel Soares Matos Sobrinho",
            "ammount": 1600.00,
            "created_at": "2021-10-09T01:30:18.000000Z",
            "updated_at": "2021-10-09T01:30:18.000000Z",
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "https://localhost/api/transactions/23"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "https://localhost/api/transactions"
                }
            ]
        }
    ]
}
```

### Detalhes de uma transação

#### Request

`GET /api/transactions/{id}`

    curl -i -H 'Accept: application/json' http://localhost/api/transactions/1

#### Response

```json
{
    "data": {
        "id": 1,
        "wallet_payer_id": 2,
        "wallet_payee_id": 3,
        "from_name": "Usuário Demonstração",
        "to_name": "Miguel Soares Matos Sobrinho",
        "ammount": 1600.00,
        "created_at": "2021-10-09T01:30:18.000000Z",
        "updated_at": "2021-10-09T01:30:18.000000Z",
        "_links": [
            {
                "rel": "self",
                "type": "GET",
                "href": "https://localhost/api/transactions/1"
            },
            {
                "rel": "create",
                "type": "POST",
                "href": "https://localhost/api/transactions"
            }
        ]
    }
}
```

### Transferir dinheiro

#### Request

`POST /api/transactions`

    curl -i -H 'Accept: application/json' -X POST -d 'wallet_payer_id=1&wallet_payee_id=2&ammount=1200' http://localhost/api/transactions

#### Payload

```json
{
    "wallet_payer_id": 1,
    "wallet_payee_id": 2,
    "ammount": 1200
}
```

#### Response

```json
{
    "data": [
        {
            "id": 90,
            "wallet_payer_id": 1,
            "wallet_payee_id": 2,
            "from_name": "Usuário Demonstração",
            "to_name": "Miguel Soares Matos Sobrinho",
            "ammount": 1200.00,
            "created_at": "2021-10-09T01:30:18.000000Z",
            "updated_at": "2021-10-09T01:30:18.000000Z",
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "https://localhost/api/transactions/90"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "https://localhost/api/transactions"
                }
            ]
        }
    ]
}
```

### Detalhes de um usuário

#### Request

`GET /api/users/{id?}`

    curl -i -H 'Accept: application/json' http://localhost/api/users/1

#### Response

```json
{
    "data": {
        "id": 1,
        "name": "Sra. Andressa Arruda Fernandes Filho",
        "email": "imontenegro@example.net",
        "balance": 8491.00,
        "created_at": "2021-10-09T01:24:39.000000Z",
        "updated_at": "2021-10-09T01:24:39.000000Z",
        "_links": [
            {
                "rel": "self",
                "type": "GET",
                "href": "https://localhost/api/users/1"
            }
        ]
    }
}
```

---

### Observações ###

Em caso de dúvida em relação aos comandos disponíveis basta executar `make` na raiz do projeto e uma lista de comandos irá aparecer com suas explicações.
