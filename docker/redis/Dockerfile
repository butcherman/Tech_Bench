#################################################################################################
#                                                                                               #
#            Redis Container holds the Redis Database for Tech Bench                            #
#                                                                                               #
#################################################################################################

FROM butcherman/tech_bench_redis:1.0

ENV REDIS_PASSWORD=tbRootPassword

USER root

RUN apt update && apt upgrade -y --no-install-recommends

WORKDIR /app
