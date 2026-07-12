################################################################################
#                                                                              #
#        Redis Container holds the Redis Database for Tech Bench               #
#                                                                              #
################################################################################

FROM redis

# REDIS Master Password
ENV REDIS_PASSWORD=tbRootPassword

# Make sure image is up to date
RUN apt update && apt upgrade -y --no-install-recommends
