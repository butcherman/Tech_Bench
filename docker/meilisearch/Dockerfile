################################################################################
#                                                                              #
#      Meilisearch Container is the Search Engine for Tech Bench               #
#                                                                              #
################################################################################

FROM getmeili/meilisearch:v1.49.0

# Meilisearch should always be in Production mode.
ARG MEILI_ENV=production
ENV MEILI_ENV=${MEILI_ENV}

# Master Key can be configured from .env file or left at default
ARG MEILI_MASTER_KEY
ENV MEILI_MASTER_KEY=${MEILI_MASTER_KEY}

# Build Healthcheck
HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=6 \
    CMD curl -f http://localhost:7700/health
