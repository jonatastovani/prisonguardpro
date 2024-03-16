FROM php:8.1-apache

# Atualize os pacotes e instale dependências necessárias
RUN apt-get update \
    && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Copie os arquivos do Laravel para o contêiner
COPY . /var/www/html

# Configure as permissões
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite
# Configure as permissões e defina o entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]

# Exponha a porta 80
EXPOSE 80

# Defina a variável de ambiente para o Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Atualize as configurações do Apache para apontar para a pasta public do Laravel
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Habilitar o Apache mod_rewrite
RUN a2enmod rewrite

# Comando padrão para iniciar o Apache ao iniciar o container
CMD ["apache2-foreground"]

# FROM php:8.1-apache

# # Atualize os pacotes e instale dependências necessárias
# RUN apt-get update \
#     && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip unzip supervisor \
#     && docker-php-ext-configure gd --with-freetype --with-jpeg \
#     && docker-php-ext-install gd pdo pdo_mysql

# # Copie os arquivos do Laravel para o contêiner
# COPY . /var/www/html

# # Configure as permissões
# RUN chown -R www-data:www-data /var/www/html \
#     && a2enmod rewrite

# # Exponha a porta 80
# EXPOSE 80

# # Defina a variável de ambiente para o Laravel
# ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# # Atualize as configurações do Apache para apontar para a pasta public do Laravel
# RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# # Habilitar o Apache mod_rewrite
# RUN a2enmod rewrite

# # Copie o arquivo de configuração do supervisor
# COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# # Inicialize o Apache e o servidor de WebSockets usando o supervisor
# CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
