#################################################################################################
#                                                                                               #
#            Database Container holds the MySQL Database for Tech Bench                         #
#                                                                                               #
#################################################################################################

FROM bitnami/mysql:latest

ENV MYSQL_ROOT_PASSWORD=tbRootPassword

COPY ./defaultDatabase.sql /docker-entrypoint-initdb.d
