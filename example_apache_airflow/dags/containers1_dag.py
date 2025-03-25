from datetime import datetime, timedelta
from airflow import DAG
from airflow.providers.docker.operators.docker import DockerOperator

default_args = {
    'owner': 'andre',
    'retries': 5,
    'retry_delay': timedelta(minutes=2)
}

with DAG(
    dag_id="containers1_dag",
    default_args=default_args,
    description="Execução de containers em PHP, atualizando um arquivo",
    start_date=datetime(2024, 3, 1),
    schedule_interval='@daily',
) as dag:
    
    task1 = DockerOperator(
        task_id='php_app1',
        image='php-app1',  # Nome da sua imagem Docker
        api_version='auto',
        auto_remove=True,
        docker_url='unix://var/run/docker.sock',
        volumes=[
            '$(pwd)/../in:/usr/src/app/in',
            '$(pwd):/usr/src/app'
        ],
        network_mode='bridge'
    )

    task2 = DockerOperator(
        task_id='php_app2',
        image='php-app2',  # Nome da sua imagem Docker
        api_version='auto',
        auto_remove=True,
        docker_url='unix://var/run/docker.sock',
        volumes=[
            '$(pwd)/../in:/usr/src/app/in',
            '$(pwd):/usr/src/app'
        ],
        network_mode='bridge'
    )