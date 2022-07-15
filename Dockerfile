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

RUN apt install php7.4-common -y
RUN apt install php7.4-cli -y

RUN apt-get install -y \
    php7.4-curl \
    php7.4-intl \
    php7.4-mysql \
    php7.4-readline \
    php7.4-xml \
    php7.4-mbstring

RUN apt-get install php7.4-xdebug # Xdebug debugger

RUN apt install apache2 -y

RUN mkdir /var/run/mysqld

RUN apt install libapache2-mod-php7.4 -y
#RUN apt-get install mariadb-server -y
RUN apt-get install mysql-server -y
RUN apt-get install git nodejs npm nano tree vim curl wget ftp -y
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

RUN mkdir /home/www
RUN chown -R www-data:www-data /home/www
RUN chmod 0777 -R /var/run/mysqld

EXPOSE 80 3306

RUN sed -i -e"s/^bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf

# ROOT PASSWORD
ENV MYSQL_ROOT_PASSWORD=password

#ENV MYSQL_DATABASE=cms
#ENV MYSQL_USER=root
#ENV MYSQL_PASSWORD=password

# Setup Mysql DB
COPY db-init.sh /db-init.sh
RUN chmod +x /db-init.sh

RUN service mysql start

COPY cs.sh /usr/sbin
RUN chmod +x /usr/sbin/cs.sh

RUN bash /usr/sbin/cs.sh

RUN bash /db-init.sh

CMD ["/usr/sbin/run-lamp.sh"]