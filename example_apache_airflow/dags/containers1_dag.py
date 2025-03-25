from datetime import datetime, timedelta
from airflow import DAG
from airflow.providers.docker.operators.docker import DockerOperator
from docker.types import Mount

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
    
    # Ajuste estes caminhos para seu ambiente REAL
    HOST_BASE_DIR = "/home/andre/www/example_comunication_docker/example_containers"
    
    task1 = DockerOperator(
        task_id='php_app1',
        image='php-app1',  # Certifique-se que esta imagem existe
        api_version='auto',
        auto_remove='success',
        docker_url='unix://var/run/docker.sock',
        mounts=[
            Mount(source=f"{HOST_BASE_DIR}/in", target="/usr/src/app/in", type="bind"),
            Mount(source=f"{HOST_BASE_DIR}/php-app1", target="/usr/src/app", type="bind")
        ],
        network_mode='bridge'
    )

    task2 = DockerOperator(
        task_id='php_app2',
        image='php-app2',  # Certifique-se que esta imagem existe
        api_version='auto',
        auto_remove='success',
        docker_url='unix://var/run/docker.sock',
        mounts=[
            Mount(source=f"{HOST_BASE_DIR}/in", target="/usr/src/app/in", type="bind"),
            Mount(source=f"{HOST_BASE_DIR}/php-app2", target="/usr/src/app", type="bind")
        ],
        network_mode='bridge'
    )