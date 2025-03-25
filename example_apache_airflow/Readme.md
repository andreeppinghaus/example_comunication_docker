# Tutorial para instalação e configuração do AirFlow

github: https://github.com/coder2j/airflow-docker
Youtube: https://www.youtube.com/watch?v=K9AnJ9_ZAXE&list=PLwFJcsJ61oujAqYpMp1kdUBcPG0sE0QMT

Baixar o docker-compose:
curl -LfO 'https://airflow.apache.org/docs/apache-airflow/2.10.5/docker-compose.yaml'

Retirar os serviços do redis e flower

Criar pastas:

mkdir -p ./dags ./logs ./plugins ./config
echo -e "AIRFLOW_UID=$(id -u)" > .env


## Subir o container com exemplos:
sudo docker-compose up airflow-init

Após terminar o build, voce verá a seguinte mensagem:

airflow-init_1       | User "airflow" created with role "Admin"
airflow-init_1       | 2.10.5
example_apache_airflow_airflow-init_1 exited with code 0


Para iniciar:

sudo docker-compose up -d

Para testar:

sudo docker ps 

CONTAINER ID   IMAGE                       COMMAND                  CREATED              STATUS                            PORTS                                                                            NAMES
f6bc320f17ce   apache/airflow:2.10.5       "/usr/bin/dumb-init …"   About a minute ago   Up 8 seconds (health: starting)   8080/tcp                                                                         example_apache_airflow_airflow-triggerer_1
dd6a86a5b1f1   apache/airflow:2.10.5       "/usr/bin/dumb-init …"   About a minute ago   Up 8 seconds (health: starting)   8080/tcp                                                                         example_apache_airflow_airflow-scheduler_1
5e242c06a245   apache/airflow:2.10.5       "/usr/bin/dumb-init …"   About a minute ago   Up 8 seconds (health: starting)   0.0.0.0:8080->8080/tcp, :::8080->8080/tcp                                        example_apache_airflow_airflow-webserver_1
a122915cbb33   postgres:13                 "docker-entrypoint.s…"   3 minutes ago        Up 37 seconds (healthy)           5432/tcp                                                                         example_apache_airflow_postgres_1

Para acessar a interface:

navegador 0.0.0.0:8080

login: airflow
senha: airflow


Para desligar o airflow basta colocar:

sudo docker-compose down -v 

onde: -v,  remove os volumes 

## Subir o container sem exemplos:

sudo docker-compose down -v

Configurar o docker-compose.yml, para sem exemplos:

Inicializar o docker novamente
sudo docker-compose up airflow-init

Subir o container 

sudo docker-compose up -d

-- demora um pouco, abrir o navegador e entrar em localhost:8080
login: airflow
senha: airflow

sudo usermod -aG docker airflow