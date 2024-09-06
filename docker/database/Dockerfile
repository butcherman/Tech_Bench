#################################################################################################
#                                                                                               #
#            Database Container holds the MySQL Database for Tech Bench                         #
#                                                                                               #
#################################################################################################

FROM bitnami/mysql:latest

ENV MYSQL_ROOT_PASSWORD=tbRootPassword
ENV MYSQL_USER=tbUser
ENV MYSQL_PASSWORD=techBenchDatabase
ENV MYSQL_DATABASE=tech-bench

# COPY ./defaultDatabase.sql /docker-entrypoint-initdb.d
