#################################################################################################
#                                                                                               #
#            NGINX Container is the main web service for Tech Bench                             #
#                                                                                               #
#################################################################################################

FROM bitnami/nginx:latest

ENV TB_URL=localhost
ENV ENABLE_HTTPS=true

USER root 

# Make sure image is up to date
RUN apt update && apt upgrade -y --no-install-recommends

# Copy configuration files
COPY ./http.conf /opt/bitnami/nginx/conf/server_blocks/tech_bench_http.conf
COPY ./https.conf /opt/bitnami/nginx/conf/server_blocks/tech_bench_https.conf

# Copy scripts
COPY ./entrypoint.sh /docker-entrypoint-startdb.d/
COPY ./entrypoint.sh /docker-entrypoint-initdb.d/

# Create a group and user to run the file permissions
RUN addgroup --gid 1024 tbgroup
RUN adduser --disabled-password --gecos "" --force-badname --ingroup tbgroup tbuser
RUN usermod -a -G root tbuser

# Make directories for storage logs and SSL Cert
RUN mkdir -p /app/storage/logs/nginx -m 775
RUN mkdir -p /app/keystore/private -m 775
RUN mkdir -p /app/public/build/assets -m 775

RUN chown tbuser:tbgroup -R /app

# Remove default files
RUN rm /app/50x.html
RUN rm /app/index.html

USER tbuser
WORKDIR /app
