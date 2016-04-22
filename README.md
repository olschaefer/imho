imho
====

Simple but powerful distributed image hosting.
Created to demonstrate the usage of Shardman library https://github.com/olschaefer/shardman

Installation:

You need composer, git, docker, docker-compose.

    mkdir imho && cd imho
    git clone https://github.com/olschaefer/imho.git .
    composer update
    cd docker
    ./run
    
That's it! Now navigate your browser to the http://<ip>:<port>/ of your docker_app_nginx_1 instance.