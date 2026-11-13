################################################################################
#                                                                              #
#        Database Container holds the MySQL Database for Tech Bench            #
#                                                                              #
################################################################################

FROM mysql:9.4

# Build Healthcheck
HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=6 \
    CMD mysqladmin ping -h localhost

# Entrypoint script creates TB Database and User
COPY docker/database/databaseInit.sql /docker-entrypoint-initdb.d/databaseInit.sql
