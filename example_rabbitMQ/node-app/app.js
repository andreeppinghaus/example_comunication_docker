const amqp = require('amqplib');

async function run() {
    const queue = 'process_queue';
    const message = JSON.stringify({ status: 'success' });

    try {
        // Conecta ao RabbitMQ
        const connection = await amqp.connect('amqp://rabbitmq');
        const channel = await connection.createChannel();

        // Cria a fila (se não existir)
        await channel.assertQueue(queue, { durable: false });

        // Publica a mensagem na fila
        channel.sendToQueue(queue, Buffer.from(message));
        console.log("Mensagem enviada para o RabbitMQ:", message);

        // Fecha a conexão
        setTimeout(() => {
            connection.close();
            process.exit(0);
        }, 500);
    } catch (error) {
        console.error("Erro ao enviar mensagem para o RabbitMQ:", error);
    }
}

// Simula um processo demorado
setTimeout(() => {
    run();
}, 5000); // 5 segundos de delay