################################################################################
#                                                                              #
#        Redis Container holds the Redis Database for Tech Bench               #
#                                                                              #
################################################################################

FROM redis:8.10.2

# Bring in custom config file
COPY redis.conf /usr/local/etc/redis/redis.conf

# Build Healthcheck
HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=3 \
    CMD redis-cli ping

CMD ["redis-server", "/usr/local/etc/redis/redis.conf"]
