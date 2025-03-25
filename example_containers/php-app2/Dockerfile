FROM php:7.4-cli
WORKDIR /usr/src/app

# Instala dependências
RUN apt-get update && \
    mkdir -p /usr/src/app/in

## somente producao
## COPY . .

CMD ["php", "index.php"]