#################################################################################################
#                                                                                               #
#            NGINX Container is the main web service for Tech Bench                             #
#                                                                                               #
#################################################################################################

FROM bitnami/nginx:latest

ENV TB_URL=localhost
ENV ENABLE_HTTPS=true

USER root 

# Copy configuration files
COPY ./http.conf /opt/bitnami/nginx/conf/server_blocks/tech_bench_http.conf
COPY ./https.conf /opt/bitnami/nginx/conf/server_blocks/tech_bench_https.conf

# Copy scripts
COPY ./entrypoint.sh /docker-entrypoint-startdb.d/

RUN apt update && apt upgrade -y --no-install-recommends

RUN mkdir -p /app/storage/logs/nginx
RUN mkdir -p /app/keystore/private

RUN chmod 775 -R /app/storage/logs
RUN chmod 775 -R /app/keystore 

WORKDIR /app
