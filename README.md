## Teste de projeto

```
Seu Nome: Ricardo Vasconcelos Fuhrmann
Nome do recrutador: Jhenifer Gustavo
Link do Linkedin: https://www.linkedin.com/in/ricardovfuhrmann
```

## Instalação local ##

Execute `make install` para executar todos os passos necessários para a inicialização do projeto. Após executar este comando, caso queira verificar a disponibilidade dos containers e se estão rodando, execute `make ps`.

No meu caso estou utilizando o **Caddy** para gerenciar meus projetos sem precisar expor as portas utilizando o docker, então adicionei uma nova regra e especifiquei como proxy reverso:

```
desafio.local.dev {
  import static
  import tls
  reverse_proxy desafio-nginx:80
}
```

## Instalação em produção ##
Execute `make build` para criar a imagem que poderá ser utilizada posteriormente. Este comando cria duas imagens prontas para produção: uma para o PHP e outra para o NGIX.

## Inspecionando o banco de dados ##

Para se conectar ao banco de dados basta executar na raiz do projeto

```make connect-db```

que o console do MySQL irá abrir em seu terminal pronto para ser utilizado.

## Testes ##

????Falar sobre testes aqui????

---

### Sobre a escolha do framework PHP ###

O Laravel hoje em dia é um dos frameworks mais utilizados e já possuo mais de 9 anos de experiência trabalhando nele. Visto que o Laravel provê agilidade no processo optei por usar ele pois traz rapidez no desenvolvimento e clareza no código.

### Sobre a escolha do banco de dados ##

Optei por utilizar MySQL pois ele atende as necessidades mais básicas até as mais avançadas.

---

### Observações ###

Obs: Em caso de dúvida em relação aos comandos basta digitar "make" na raiz do projeto e uma lista de comandos irá aparecer com suas explicações.
