#################################################################################################
#                                                                                               #
#                      Tech Bench build for main Tech Bench Application                         #
#                                                                                               #
#################################################################################################

FROM butcherman/tech_bench_baseline:1.0

COPY ./php.ini /opt/bitnami/php/lib/
COPY ./entrypoint.dev.sh /scripts/
COPY ./setup.dev.sh /scripts/
COPY ./.env.dev /scripts/.env.dev

RUN chmod 775 /scripts/entrypoint.dev.sh
RUN chmod 775 /scripts/setup.dev.sh
RUN chmod 777 /scripts/.env.dev

#  Make sure image is up to date
RUN apt update && apt upgrade -y --no-install-recommends

#  Install xDebug to allow PHPUnit Testing
RUN apt install php-xdebug -y
RUN apt install php-mysql -y

#  Create a group and user to run the file permissions
RUN addgroup --gid 1024 tbgroup
RUN adduser --disabled-password --gecos "" --force-badname --ingroup tbgroup tbuser
RUN usermod -a -G root tbuser
RUN chmod 775 /var/log

#  Set directory permissions
RUN chown -R tbuser:tbgroup /app
RUN find /app/ -type f -exec chmod 777 {} \;
RUN find /app/ -type d -exec chmod 777 {} \;

USER tbuser
WORKDIR /app

ENTRYPOINT [ "/scripts/entrypoint.dev.sh" ]
