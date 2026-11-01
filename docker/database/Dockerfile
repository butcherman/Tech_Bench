################################################################################
#                                                                              #
#        Database Container holds the MySQL Database for Tech Bench            #
#                                                                              #
################################################################################

FROM mysql:9.4

################################################################################
# Healthcheck
################################################################################

HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=6 \
    CMD mysqladmin ping -h localhost

################################################################################
# Finalize Image/Create Entrypoint
################################################################################

COPY ./databaseInit.sql /docker-entrypoint-initdb.d/databaseInit.sql
