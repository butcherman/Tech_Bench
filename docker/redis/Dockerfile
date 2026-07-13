################################################################################
#                                                                              #
#        Redis Container holds the Redis Database for Tech Bench               #
#                                                                              #
################################################################################

FROM redis

# Bring in custom config file
COPY redis.conf /usr/local/etc/redis/redis.conf

# Make sure image is up to date
RUN apt update && apt upgrade -y --no-install-recommends

CMD ["redis-server", "/usr/local/etc/redis/redis.conf"]
