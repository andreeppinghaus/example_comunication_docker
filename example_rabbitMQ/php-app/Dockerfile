FROM php:7.4-cli
WORKDIR /usr/src/app
COPY . .
RUN apt-get update && apt-get install -y librabbitmq-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp
CMD ["php", "index.php"]