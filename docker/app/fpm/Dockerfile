FROM php:7-fpm

# dev
ENV SYMFONY__APP_CACHE_DIR_PREFIX /tmp/symfony-cache
# dev
ENV SYMFONY__APP_LOG_DIR          /tmp/symfony-logs

RUN apt-get update && apt-get install -y git zlib1g-dev libpq-dev # libmagickwand-dev libmagickcore-dev # dev
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install zip # dev
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pcntl

RUN echo "date.timezone=UTC\nupload_max_filesize=15M" > /usr/local/etc/php/conf.d/timezone.ini

#RUN ln -s /usr/lib/x86_64-linux-gnu/ImageMagick-6.8.9/bin-Q16/MagickWand-config /usr/bin/ && \
#    ln -s /usr/lib/x86_64-linux-gnu/ImageMagick-6.8.9/bin-Q16/Wand-config /usr/bin/ && \
#    printf "\n" | pecl install imagick && \
#    printf "extension=imagick.so" > /usr/local/etc/php/conf.d/imagick.ini

VOLUME ["/home"]
VOLUME ["/shardman"] # dev
VOLUME ["/shardman-symfony-bundle"] # dev

# dev
RUN git config --global user.name "Oliver Schaefer" && git config --global user.email "olschaefer@olschaefer.com"
RUN echo -n "memory_limit = 512M" > /usr/local/etc/php/conf.d/memory.ini # dev
RUN printf "\npm.max_children=20\n" >> /usr/local/etc/php/php-fpm.conf

#EXPOSE 5501 5502 5503 5504 5505 5506 5507 5508 8080
#CMD ["/home/vendor/php-pm/php-pm/bin/ppm", "start", "/home", "--bridge=httpKernel", "--host=docker_app_fpm_1"]