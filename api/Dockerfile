FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

# update packages and install project dependencies
RUN apt update
RUN apt upgrade -y
RUN apt install sqlite -y
RUN apt install apache2 php php8.1-sqlite3 libapache2-mod-php -y
RUN apt install python3 python3-pip -y
RUN apt install libgl1 libglib2.0-0 -y
RUN apt install vim -y

# copy apache configuration
COPY ./conf/apache2.conf /etc/apache2/apache2.conf
# copy php configuration
COPY ./conf/php.ini /etc/php/8.1/apache2/php.ini

# RUN pip3 install opencv-python requests

# copy the files to the container
COPY . /var/www/html

WORKDIR /var/www/html
RUN chmod -R +rw .

# expose http port for apache
EXPOSE 80

# start apache on foreground
CMD ["/usr/sbin/apache2ctl", "-DFOREGROUND"]

# ENTRYPOINT [ "./entrypoint.sh" ]