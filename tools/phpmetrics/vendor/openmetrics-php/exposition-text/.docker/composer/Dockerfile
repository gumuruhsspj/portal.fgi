FROM composer

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Update system
RUN set -ex && apk update && apk upgrade

# Install xdebug
RUN set -ex && install-php-extensions xdebug-^3.5

# Cleanup
RUN rm -rf /var/cache/apk/*