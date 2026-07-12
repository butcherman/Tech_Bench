################################################################################
#                                                                              #
#        Database Container holds the MySQL Database for Tech Bench            #
#                                                                              #
################################################################################

FROM mysql:9.4

# MySql Root Password can be configured from the .env file, or use default
ARG MYSQL_ROOT_PASSWORD
ENV MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD}

# Entrypoint script creates TB Database and User
COPY ./databaseInit.sql /docker-entrypoint-initdb.d/databaseInit.sql
