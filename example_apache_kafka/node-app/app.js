const { Kafka, logLevel } = require('kafkajs');

async function connectToKafka() {
    const kafka = new Kafka({
        clientId: 'node-app',
        brokers: ['kafka:9092'],
        logLevel: logLevel.ERROR, // Define o nível de log para evitar logs desnecessários
    });

    const producer = kafka.producer();

    try {
        await producer.connect();
        console.log("Conectado ao Kafka!");
        return producer;
    } catch (error) {
        console.error("Erro ao conectar ao Kafka:", error.message);
        throw error;
    }
}

async function run() {
    const maxAttempts = 5; // Número máximo de tentativas
    let attempt = 0;
    let producer;

    while (attempt < maxAttempts) {
        attempt++;
        console.log(`Tentativa ${attempt} de ${maxAttempts}...`);

        try {
            producer = await connectToKafka();
            break; // Sai do loop se a conexão for bem-sucedida
        } catch (error) {
            if (attempt === maxAttempts) {
                console.error("Número máximo de tentativas atingido. Abortando...");
                process.exit(1);
            }
            console.log("Tentando novamente em 5 segundos...");
            await new Promise(resolve => setTimeout(resolve, 5000)); // Aguarda 5 segundos
        }
    }

    const topic = 'process_queue';
    const message = { status: 'success' };

    try {
        await producer.send({
            topic,
            messages: [
                { value: JSON.stringify(message) }
            ]
        });
        console.log("Mensagem enviada para o Kafka:", message);
    } catch (error) {
        console.error("Erro ao enviar mensagem para o Kafka:", error.message);
    } finally {
        await producer.disconnect();
    }
}

// Simula um processo demorado
setTimeout(() => {
    run().catch(error => {
        console.error("Erro no processo principal:", error);
    });
}, 5000); // 5 segundos de delay