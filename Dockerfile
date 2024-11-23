#FROM php:7.2.2-apache
#RUN docker-php-ext-install mysqli

FROM php:7.2.2-apache

# Instalar la extensión mysqli para PHP
RUN docker-php-ext-install mysqli

# Habilitar mod_headers en Apache
RUN a2enmod headers

# Habilitar virtual host predeterminado
RUN a2ensite 000-default

# Copiar el archivo de configuración al contenedor
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf


# Iniciar Apache en el contenedor
CMD ["apache2ctl", "-D", "FOREGROUND"]
