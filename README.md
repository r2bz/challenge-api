# Challenge API

## Esta API simula uma ferramenta básica de receção de alertas e geração de incidentes

Descrição: Trata-se de uma **API REST** que será responsável por realizar as seguintes funcionalidades:

- **Gestão de alertas**;
- **Gestão de incidentes**; 
- **Exposição de métricas**;
- **Exposição de saúde da aplicação**.

### Ambiente

Foi disponibilizado neste repositório o ambiente de desenvolvimento da aplicação em docker-compose.
Para configurar o ambiente basta seguir os passos abaixo:
git clone https://github.com/r2bz/challenge-api.git
cd challenge-api
docker-compose up -d

Serão inciados:
- um container com PHP 7.4 e Apache 2 [utiliza a porta 80]
- um container com a base em mysql 5 [utiliza a porta 3306]
- um container que simula uma aplicação enviando métricas para a API

Fique a vontade para trocar as portas utilizadas. Lembre-se de alterar o script metrics-generator.sh disponível na raíz do projeto


### A API é composta basicamente de quatro endpoints
- *alert*
- *recive*
- *metrics*
- *health*


### Utilização:
Para acessar / utilizar a API basta fazer uma requisição com os seguintes parâmetros:
<method> http://<domain>:<port>/api/<endpoint>/<id>/<subfunction>
Em que:
<method> -> método utilizado na requisição. Ex.: GET, POST, PUT, PATCH, DELETE
<domain> -> domínio em que a aplicação está hospedada. Ex.: http://meudominio.com/api/metrics
<port> -> porta em que o servidor de aplicação está escutando.
<endpoint> -> endpoint a ser acessado.
<id> -> Caso a função acessada disponibilize, número inteiro indicando a chave de um determinado registro.
<subfunction> -> Caso a função acessada disponibilize, subfunção da opção acessada.


Para o primeiro endpoint api/alert foram implementados as seguintes formas de interação com a API:

GET http://<domain>/api/alert -> Retorna uma lista com as configurações de todos os alertas
GET http://<domain>/api/alert/<id> -> Retorna as configurações do alerta com id = <id>
POST http://<domain>/api/alert -> Cria um alerta
PUT http://<domain>/api/alert/<id> -> Atualizaria completamente o alerta em que o ID = <id>
PATCH http://<domain>/api/alert/<id>/enabled -> Atualiza parcialmente o alerta 1, campo enabled
DELETE http://<domain>/api/alert/<id> -> Remove o alerta em que o ID = <id>


Exemplo de uso:
Através do método GET ou através de um browser digite a url em que a aplicação está hospedada
Caso a aplicação esteja executando localmente
http://localhost/api/alert para listar todos os alertas



ENDPOINT api/receive
GET /receive -> Retorna todas as métricas
GET /receive/1 -> Retorna a métrica com id = 1
POST /receive -> Recebe uma métrica e insere na base
PUT /receive/1 ->            Não implementado
PATCH /receive/1/appName ->  Não implementado
DELETE /receive/1 ->         Não implementado

ENDPOINT api/metrics
Report com o resumos sobre métricas e incidentes
GET /receive -> Retorna todas as métricas
GET /receive/1 -> Retorna a métrica com id = 1
POST /receive -> Recebe uma métrica e insere na base
PUT /receive/1 ->            Não implementado
PATCH /receive/1/appName ->  Não implementado
DELETE /receive/1 ->         Não implementado


ENDPOINT api/health
GET /receive -> Retorna todas as métricas
GET /receive/1 -> Retorna a métrica com id = 1
POST /receive -> Recebe uma métrica e insere na base
PUT /receive/1 ->            Não implementado
PATCH /receive/1/appName ->  Não implementado
DELETE /receive/1 ->         Não implementado








## Implementação em Cloud
Como proposta para implementar esta solução de API na núvem da AWS. Segue desenho  
Link para a arquitetura em cloud:
https://app.cloudcraft.co/view/859dbb83-b8e8-40cc-ab5d-a982bd7daf43?key=Lmj6uQ8XxDW8rXq1YmQtqQ

Se formos utilizar a infraestrutura de containers da forma como a API já está empacotada, seria basicamente:

Usuário da API --- Application Load Balancer (ALB) --- {EC2 ou ECS (Docker-based) ou EKS (Kubernetes-based) com [Apache/PHP]} --- RDS (MySQL)

Pois da forma que está, o MySQL poderia ser facilmente migrado para um Relational Database Service (RDS)

Basicamente como está tudo em containers e o Runtime da aplicação foi definido, nos deixou menos opções comparado a outras linguagens mais "modernas".





### Qual ou quais ferramentas de automação utilizaria
Supondo que seria implementado na AWS, diria que pode-se usar:

Para Infraestrutura
- ECS (mais provavelmente com EC2 por conta do MySQL)
- ECR (image registry, tipo DockerHub)
- ALB (Application Load Balancer)
- CloudWatch Containers Insights, CloudWatch Logs, CloudWatch (metricas, alertas, etc)

Automação
- Pipeline usando AWS CodePipeline e composto por CodeCommit (git repository), CodeBuild e CodeDeploy. Neste caso, artefatos armazenados em S3.
- Infraestrutura pode ser facilmente construída usando CloudFormation, seria mais simples.

Movendo o banco fora do container MySQL por um RDS ou Aurora Serverless, bem como programar a API em outra linguaguem com suporte nativo ao Lambda por exemplo. Poderiamos chegar a 100% stateless e usar Fargate (spot ou on-demand) ou mesmo EC2 spot e reduzir custo.

Como pode-se entregar esta API em outras liguaguens, é possível inicialmente usar da forma que está, em containers, e eventualmente mudar para Lambda com API Gateway.
(PHP pode ser usado como um bring your own runtime)
ou eventualmente re-escreve a API em algo como JavaScript (NodeJS runtime), Python, Ruby, Go...
Como um mecanismo adicional, com pouca ou nenhuma mudança, você pode-se usar o AWS WAF (Web Application Firewall) para adicionar alguma proteção à API.

Pode-se trocar o ALB por um API Gateway diretamente, com isso, traz o benefício de implementar autenticação sem precisar tocar na aplicação.


Solução 1: API User --- ALB (optional WAF) --- ECS/EKS (Containers)
Solução 2: API User --- API Gateway (optional WAF) --- ECS/EKS (Containers)
Solução 3: API User --- API GAteway (optional WAF) --- Lambda Functions --- Database (RDS)


### Melhoria da verificação da saúde da aplicação

O endpoint /health o qual já retorna HTTP/200 se a dependência da API, no caso, o banco estiver OK.
O raciocício foi, se o banco está disponível, a API está disponível.

Portanto como proposta de melhoria, o /health precisa confirmar que o banco está disponível antes de responder
no target group do load balancer, seria configurado o health check para apontar para o endpoint, é possível também, publicar o status no CloudWatch via metrics
e usar o cloudwatch alarms para monitorar o que define saudavel e configurar alguma ação quando estiver com problemas.

Se for necessário continuar com a infraestrutura em containers também é possível monitorar, 
CPU, Memoria e Rede dos containers da API, quantidade de containers ativos versus esperados.
É possível criar uma dashboard no cloudwatch com essas metricas em paineis e ativar alarmes.
Por exemplo: se o uso de memoria RAM ultrapassar 10GB por 5 minutos alerta que a memória tá alta.
Da mesma forma para CPU e rede é possível configurar alarmes.

O CloudWatch Anomaly Detection pode também ajudar a detectar situações anormais.
Containers Insights precisa ser ativado a nivel de conta ou cluster


## Como proposta de melhoria da API
Reestruturar a base de acordo com o diagrama disponível na raíz do projeto com o nome DiagramaEER_proposta.png
Aplicando os relacionamentos do Diagrama novas entidades devem ser alimentadas antes de se inserir dados em outras, 
exigirá novos endpoints paa controle específico de entidades.
Implementar autenticação e autorização.
Adicionar mecanismo de enfileiramento de mensagens.
Estudar possibilidade de utilizar base não relacional para armazenar os dados de métricas.

*Repetindo trecho de comentário anterior*
Movendo o banco fora do container MySQL por um RDS, bem como programar a API em outra 
linguaguem com suporte nativo ao Lambda por exemplo. Poderiamos chegar a 100% stateless e usar 
Fargate (spot ou on-demand) ou mesmo EC2 spot e reduzir custo.