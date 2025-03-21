FROM php:7.4-cli
WORKDIR /usr/src/app

# Instala dependências e a extensão rdkafka
RUN apt-get update && apt-get install -y librdkafka-dev \
    && pecl install rdkafka \
    && docker-php-ext-enable rdkafka

COPY . .

CMD ["php", "index.php"]