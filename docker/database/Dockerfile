#################################################################################################
#                                                                                               #
#            Database Container holds the MySQL Database for Tech Bench                         #
#                                                                                               #
#################################################################################################

FROM butcherman/tech_bench_database:1.0

RUN apt install && apt upgrade -y --no-install-recommends

ENV MYSQL_ROOT_PASSWORD=tbRootPasswd
ENV MYSQL_USER=tbUser
ENV MYSQL_PASSWORD=techBenchDatabase
ENV MYSQL_DATABASE=tech-bench

COPY ./defaultDatabase.sql /docker-entrypoint-initdb.d/
