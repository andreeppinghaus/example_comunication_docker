
from datetime import datetime, timedelta

from airflow import DAG
from airflow.operators.bash import BashOperator

default_args = {
    'owner': 'andreeppinghaus',
    'retries': 5,
    'retry_delay': timedelta(minutes=2)
}
with DAG(
    dag_id = "our_first_dag",
    default_args = default_args,
    description = "Este e o primeiro dag que iremos testar",
    start_date=datetime(2023, 2, 1),
    schedule_interval='@daily',
) as dag:
    task1 = BashOperator(
        task_id ='first_task',
        bash_command='echo helo world, this is first task!'
    )
