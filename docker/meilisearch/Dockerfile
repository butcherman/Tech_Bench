################################################################################
#                                                                              #
#      Meilisearch Container is the Search Engine for Tech Bench               #
#                                                                              #
################################################################################

FROM getmeili/meilisearch:v1.49.0

# Meilisearch should always be in Production mode.
ARG MEILI_ENV=production
ENV MEILI_ENV=${MEILI_ENV}

################################################################################
# Healthcheck
################################################################################

HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=6 \
    CMD curl -f http://localhost:7700/health
