# Usa a imagem base com PHP 7.4 e OCI8
FROM blacknoob20/php7.4-fpm-alpine-oci8

# Define o diretório de trabalho
WORKDIR /var/www/html

# Instala dependências do sistema
RUN apk add --no-cache git unzip curl bash supervisor

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia os arquivos do projeto Symfony
COPY . /var/www/html

# Corrige permissões
RUN chmod -R 775 /var/www/html/var /var/www/html/public

# Instala as dependências do Symfony
RUN composer install --no-interaction --optimize-autoloader --no-scripts

# Garante que o diretório de logs esteja acessível
RUN mkdir -p /var/www/html/var/log && chmod -R 777 /var/www/html/var/log

# Expõe a porta do Symfony
EXPOSE 8000

# Comando para iniciar o servidor Symfony dentro do container
CMD php -S 0.0.0.0:8000 -t public
