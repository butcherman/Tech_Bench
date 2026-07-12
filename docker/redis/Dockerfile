################################################################################
#                                                                              #
#        Redis Container holds the Redis Database for Tech Bench               #
#                                                                              #
################################################################################

FROM redis

# Redis password can be configured from .env or left at default
ARG REDIS_PASSWORD
ENV REDIS_PASSWORD=${REDIS_PASSWORD}

# Make sure image is up to date
RUN apt update && apt upgrade -y --no-install-recommends
