################################################################################
#                                                                              #
#        Database Container holds the MySQL Database for Tech Bench            #
#                                                                              #
################################################################################

FROM mysql:9.4

ENV MYSQL_ROOT_PASSWORD=tbRootPasswd
ENV MYSQL_USER=tbUser
ENV MYSQL_PASSWORD=techBenchDatabase
ENV MYSQL_DATABASE=tech-bench

COPY ./defaultDatabase.sql /docker-entrypoint-initdb.d/
