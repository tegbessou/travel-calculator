FROM php:7.4-fpm-alpine

RUN apk update \
 && apk add --no-cache $PHPIZE_DEPS \
    bash \
    git \
    zip \
    unzip \
    nodejs \
    python2

# PHP extensions
RUN docker-php-ext-install opcache pdo_mysql mysqli
RUN docker-php-ext-enable opcache

RUN docker-php-source extract \
    && curl -L -o /tmp/redis.tar.gz https://github.com/phpredis/phpredis/archive/5.3.2.tar.gz \
    && tar xfz /tmp/redis.tar.gz \
    && rm -r /tmp/redis.tar.gz \
    && mv phpredis-5.3.2 /usr/src/php/ext/redis \
    && docker-php-ext-install redis

RUN apk add --update icu-dev \
	&& docker-php-ext-configure intl \
	&& docker-php-ext-install intl

# Install composer
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
RUN wget -O /usr/local/bin/local-php-security-checker "https://github.com/fabpot/local-php-security-checker/releases/download/v1.0.0/local-php-security-checker_1.0.0_linux_amd64"
RUN chmod +x /usr/local/bin/local-php-security-checker

# Install yarn
ADD https://yarnpkg.com/latest.tar.gz /opt/yarn.tar.gz
RUN cd /opt \
    && mkdir yarn \
    && tar xzf yarn.tar.gz -C yarn --strip-components 1 \
    && cd /usr/local/bin \
    && ln -s /opt/yarn/bin/yarn

# Upgrade bash configuration
COPY .bashrc /home/www-data/.bashrc

#######################
# Override the image  #
# ENTRYPOINT to add   #
# some logic          #
#######################
COPY entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint
ENTRYPOINT ["entrypoint"]