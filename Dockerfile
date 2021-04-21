FROM ubuntu

RUN apt-get update
RUN apt-get upgrade -y

ENV TZ=Europe/Moscow
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get install software-properties-common -y
RUN add-apt-repository ppa:ondrej/php

RUN apt-get update
RUN apt-get upgrade -y

RUN apt-get install -y zip unzip

RUN apt install php8.0-common -y
RUN apt install php8.0-cli -y

RUN apt-get install -y \
    php8.0-curl \
    php8.0-intl \
    php8.0-mysql \
    php8.0-readline \
    php8.0-xml \
    php8.0-mbstring

RUN apt-get install php8.0-xdebug # Xdebug debugger

RUN apt install apache2 -y

RUN apt install libapache2-mod-php8.0 -y
#RUN apt-get install mariadb-server -y
RUN apt-get install mysql-server -y
RUN apt-get install git nodejs npm composer nano tree vim curl ftp -y
RUN npm install -g bower grunt-cli gulp

RUN apt install mc -y

ENV LOG_STDOUT **Boolean**
ENV LOG_STDERR **Boolean**
ENV LOG_LEVEL warn
ENV ALLOW_OVERRIDE All
ENV DATE_TIMEZONE UTC


COPY run-lamp.sh /usr/sbin/
COPY 000-default.conf /etc/apache2/sites-available/

VOLUME /home/www
VOLUME /var/log/httpd
VOLUME /var/lib/mysql
VOLUME /var/log/mysql
VOLUME /etc/apache2

RUN a2enmod rewrite
#RUN ln -s /usr/bin/nodejs /usr/bin/node

RUN chmod +x /usr/sbin/run-lamp.sh

#RUN chown -R www-data:www-data /home/www

EXPOSE 3306 80

RUN sed -i -e"s/^bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf

CMD ["/usr/sbin/run-lamp.sh"]