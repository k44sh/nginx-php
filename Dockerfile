ARG ALPINE_VERSION=3.17
ARG ALPINE_S6_VERSION=${ALPINE_VERSION}-2.2.0.3
ARG USER=nginx-php

FROM crazymax/alpine-s6:${ALPINE_S6_VERSION} as builder
RUN apk --update --no-cache add curl

ENV S6_BEHAVIOUR_IF_STAGE2_FAILS="2" \
  S6_KILL_GRACETIME="5000" \
  TZ="UTC" \
  PUID="1000" \
  PGID="1000"

ARG USER
RUN echo "@314 http://dl-cdn.alpinelinux.org/alpine/v3.14/main" >> /etc/apk/repositories
RUN apk --update --no-cache add \
    apache2-utils \
    bash \
    bind-tools \
    curl \
    ffmpeg \
    geoip \
    nano \
    nginx \
    nginx-mod-http-headers-more \
    nginx-mod-http-dav-ext \
    nginx-mod-http-geoip2 \
    nginx-mod-rtmp \
    php81 \
    php81-dev \
    php81-bcmath \
    php81-cli \
    php81-ctype \
    php81-curl \
    php81-fpm \
    php81-json \
    php81-mbstring \
    php81-openssl \
    php81-opcache \
    php81-pecl-apcu \
    php81-pear \
    php81-phar \
    php81-posix \
    php81-session \
    php81-sockets \
    php81-xml \
    php81-zip \
    php81-zlib \
  && addgroup -g ${PGID} ${USER} \
  && adduser -D -H -u ${PUID} -G ${USER} -s /bin/sh ${USER} \
  && nginx -v && php81 -v \
  && rm -rf /tmp/* /var/cache/apk/*

RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log && \
    ln -sf /dev/stdout /var/log/php81/access.log && \
    ln -sf /dev/stderr /var/log/php81/error.log \
    ln -sf /dev/stdout /var/log/nginx/stream.log

COPY rootfs /

VOLUME [ "/etc/nginx", "/var/www" ]

ENTRYPOINT [ "/init" ]

HEALTHCHECK --interval=10s --timeout=5s --start-period=5s CMD /usr/local/bin/healthcheck