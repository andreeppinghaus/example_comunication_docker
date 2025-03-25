
from datetime import datetime, timedelta

from airflow import DAG
from airflow.operators.bash import BashOperator

default_args = {
    'owner': 'andre',
    'retries': 5,
    'retry_delay': timedelta(minutes=2)
}
with DAG(
    dag_id = "containers1_dag",
    default_args = default_args,
    description = "Execucação de containers em PHP, atualizando um arquivo",
    start_date=datetime(2024, 3, 1),
    schedule_interval='@daily',
) as dag:
    task1 = BashOperator(
        task_id ='php_app1',
        bash_command='docker run -v $(pwd)/../in:/usr/src/app/in -v $(pwd):/usr/src/app php-app1'
    )

    task2 = BashOperator(
        task_id ='php_app2',
        bash_command='docker run -v $(pwd)/../in:/usr/src/app/in -v $(pwd):/usr/src/app php-app2'
    )
