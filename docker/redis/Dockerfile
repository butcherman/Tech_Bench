#################################################################################################
#                                                                                               #
#            Redis Container holds the Redis Database for Tech Bench                            #
#                                                                                               #
#################################################################################################

FROM bitnami/redis:latest

ENV REDIS_PASSWORD=tbRootPassword

USER root

CMD ["redis-server", "/usr/local/etc/redis/redis.conf"]
