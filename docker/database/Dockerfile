#################################################################################################
#                                                                                               #
#            Database Container holds the MySQL Database for Tech Bench                         #
#                                                                                               #
#################################################################################################

FROM bitnami/mysql:latest

COPY ./defaultDatabase.sql /docker-entrypoint-initdb.d
