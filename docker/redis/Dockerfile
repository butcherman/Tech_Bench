################################################################################
#                                                                              #
#        Redis Container holds the Redis Database for Tech Bench               #
#                                                                              #
################################################################################

FROM redis:alpine

# REDIS Master Password
ENV REDIS_PASSWORD=tbRootPassword

# Make sure image is up to date
RUN apk update && apk upgrade
