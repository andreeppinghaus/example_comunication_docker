const amqp = require('amqplib');

async function run() {
    const queue = 'process_queue';
    const message = JSON.stringify({ status: 'success' });

    let connection;
    let maxAttempts = 10;
    let attempt = 0;

    while (attempt < maxAttempts) {
        try {
            // Tenta conectar ao RabbitMQ
            connection = await amqp.connect('amqp://meu_usuario:minha_senha@rabbitmq');
            console.log("Conectado ao RabbitMQ!");
            break;
        } catch (error) {
            attempt++;
            console.log(`Tentativa ${attempt} de ${maxAttempts}: RabbitMQ não está disponível. Tentando novamente em 5 segundos...`);
            await new Promise(resolve => setTimeout(resolve, 5000)); // Aguarda 5 segundos
        }
    }

    if (attempt === maxAttempts) {
        throw new Error("Não foi possível conectar ao RabbitMQ após várias tentativas.");
    }

    const channel = await connection.createChannel();

    // Exclui a fila existente (se houver)
    //await channel.deleteQueue(queue);

    await channel.assertQueue(queue, {
        durable: false,  // Fila não é persistente
        autoDelete: true // Fila é automaticamente excluída quando não há consumidores 
    });

    channel.sendToQueue(queue, Buffer.from(message));
    console.log("Mensagem enviada para o RabbitMQ:", message);

    setTimeout(() => {
        connection.close();
        process.exit(0);
    }, 500);
}

// Simula um processo demorado
setTimeout(() => {
    run().catch(error => {
        console.error("Erro ao enviar mensagem para o RabbitMQ:", error);
    });
}, 5000); // 5 segundos de delay