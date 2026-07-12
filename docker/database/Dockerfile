################################################################################
#                                                                              #
#        Database Container holds the MySQL Database for Tech Bench            #
#                                                                              #
################################################################################

FROM mysql:9.4

# Entrypoint script creates TB Database and User
COPY ./databaseInit.sql /docker-entrypoint-initdb.d/databaseInit.sql
