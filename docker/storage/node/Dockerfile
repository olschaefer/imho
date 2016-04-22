FROM debian:jessie

# Nginx docs for lua support (http://wiki.nginx.org/HttpLuaModule) recommend using
# openresty

# Via http://openresty.org
RUN apt-get -y update && \
    apt-get -y install libreadline-dev \
                    libncurses5-dev \
                    libpcre3-dev \
                    libssl-dev \
                    perl \
                    make \
                    build-essential \
                    wget \
                    libexpat1-dev \
                    dnsmasq


ADD dav-ext-module /root/dav-ext-module

RUN wget http://openresty.org/download/ngx_openresty-1.9.3.1.tar.gz && \
    tar xzvf ngx_openresty-1.9.3.1.tar.gz && \
    cd ngx_openresty-1.9.3.1 && \
    ./configure --with-http_dav_module --with-luajit --add-module=/root/dav-ext-module && \
    make && \
    make install

RUN ln -sf /usr/local/openresty/nginx/sbin/nginx /usr/local/sbin/nginx

RUN mkdir -p /var/log/nginx && \
    ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

#################################################

RUN ln -s /dev/stdout /var/log/nginx/storage_node_access.log
RUN ln -s /dev/stderr /var/log/nginx/storage_node_error.log
RUN mkdir /storage && chown nobody:nogroup /storage

RUN mkdir /usr/local/openresty/nginx/conf/conf.d
COPY nginx.conf /usr/local/openresty/nginx/conf/nginx.conf
COPY storage_node.conf /usr/local/openresty/nginx/conf/conf.d/storage_node.conf

EXPOSE 80
CMD ["/usr/local/sbin/nginx", "-g", "daemon off;"]