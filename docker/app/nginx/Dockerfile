FROM camptocamp/nginx-extras

RUN ln -s /dev/stdout /var/log/nginx/app_access.log
RUN ln -s /dev/stderr /var/log/nginx/app_error.log
RUN rm /etc/nginx/sites-enabled/*

COPY imho.conf /etc/nginx/conf.d/default.conf
VOLUME ["/home"]
EXPOSE 80