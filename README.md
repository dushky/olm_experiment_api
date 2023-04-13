<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Installation

1. PHP 8, Composer, PHP extensions (Described on Laravel site), Python 3.8 is **required**
2. run ```composer install``` to install project dependencies
3. run your MySQL server
4. create ```.env``` file from ```.env.example```
5. set ```APP_HOST``` variable to ip address of server running on
6. set your database connection information in the ```DB_CONNECTION```, ```DB_HOST```, ```DB_PORT```, ```DB_DATABASE```, ```DB_USERNAME```, ```DB_PASSWORD```
7. set ```BROADCAST_DRIVER``` variable to ```pusher```
8. set ```QUEUE_CONNECTION``` variable to ```database```
9. set ```PUSHER_APP_ID``` variable to whatever you want (recommended ```local```)
10. set ```PUSHER_APP_KEY``` variable to whatever you want (recommended ```local```)
11. set ```PUSHER_APP_SECRET``` variable to whatever you want (recommended ```local```)
12. set ```ADMIN_EMAIL``` to the email address of admin account (whatever you want)
13. set ```ADMIN_PASSWORD``` to the password of admin account (whatever you want)
14. in production set ```LARAVEL_WEBSOCKETS_SSL_LOCAL_CERT``` to the absolute path of SSL certificate
15. in production set ```LARAVEL_WEBSOCKETS_SSL_LOCAL_PK``` to the absolute path of certificate keys
16. run ```php artisan key:generate``` to generate application key
17. run ```php artisan migrate``` to run database migrations
18. run ```php artisan db:seed``` to seed created database
19. run ```php artisan passport:install``` to generate passport keys
20. second client id from previous command output copy to the ```PASSPORT_CLIENT_ID``` 
21. second secret key from previous command output copy to the ```PASSPORT_CLIENT_SECRET``` variable in ```.env``` file
22. set nginx vhost root to public directory of the project
23. in dev enviroment:
    1. run ```php artisan websockets:serve``` to run websockets server on :6001 port
    2. run ```php artisan queue:listen --queue=broadcast-queue``` to run queue for writing data in websockets channel
    3. run ```php artisan queue:listen --queue=Reading``` to run queue for reading experiments outputs
24. in production enviroment:
    1. use Supervisor for running websockets server and queues
    2. install Supervisor on the server
    3. in ```/etc/supervisor/conf.d/``` directory create ```laravel-worker.conf``` file and paste this:
    ```
    [program:laravel-worker]
    process_name=%(program_name)s_%(process_num)02d
    command=/usr/bin/php /var/www/"YOUR_APP_FOLDER"/artisan queue:listen --queue=Reading  --sleep=3 --tries=3 --timeout=0
    autostart=true
    autorestart=true
    stopasgroup=true
    killasgroup=true
    user=iolab
    numprocs=1
    redirect_stderr=true
    stdout_logfile=/var/www/"YOUR_APP_FOLDER"/storage/logs/worker.log
    stopwaitsecs=3600
    ```
    4. in ```/etc/supervisor/conf.d/``` directory create ```laravel-websockets-worker.conf``` file and paste this:
    ```
    [program:laravel-websockets-worker]
    process_name=%(program_name)s_%(process_num)02d
    command=/usr/bin/php /var/www/"YOUR_APP_FOLDER"/artisan queue:listen --queue=broadcast-queue  --sleep=3 --tries=3
    autostart=true
    autorestart=true
    stopasgroup=true
    killasgroup=true
    user=iolab
    numprocs=8
    redirect_stderr=true
    stdout_logfile=/var/www/"YOUR_APP_FOLDER"/storage/logs/worker.log
    stopwaitsecs=3600
    ```
    5. in ```/etc/supervisor/conf.d/``` directory create ```laravel-websocket.conf``` file and paste this:
    ```
    [program:laravel-websocket]
    process_name=%(program_name)s_%(process_num)02d
    command=/usr/bin/php /var/www/"YOUR_APP_FOLDER"/artisan websockets:serve
    autostart=true
    autorestart=true
    stopasgroup=true
    killasgroup=true
    user=iolab
    numprocs=1
    redirect_stderr=true
    stdout_logfile=/var/www/"YOUR_APP_FOLDER"/storage/logs/websockets.log
    stopwaitsecs=3600
    ```
    6. run ```sudo supervisorctl reread```, ```sudo supervisorctl update``` and ```sudo supervisorctl
       start all```

## Video Stream

For providing live video stream of experiment please connect camera to the server using nginx web server and follow these instructions:
1. run ```apt-get --yes update``` and ```apt-get --yes install wget build-essential libaio1 openssl libpcre3-dev libssl-dev zlib1g-dev ffmpeg;``` to install dependencies
2. run ```sudo apt install libnginx-mod-rtmp``` to install rtmp nginx module
3. to `nginx.conf` http section add following to publish hls video stream:
```
server {  
             listen 8080;

         
             # Client (VLC etc.) can access HLS here.
             location /hls {
               # Serve HLS fragments
               types {
                 application/vnd.apple.mpegurl m3u8;
                 video/mp2t ts;
               }
               root /tmp/;
               add_header 'Cache-Control' 'no-cache';
               add_header 'Access-Control-Allow-Origin' '*' always;
               add_header 'Access-Control-Expose-Headers' 'Content-Length,Content-Range';
               add_header 'Access-Control-Allow-Headers' 'Range';
               if ($request_method = 'OPTIONS') {
                     add_header 'Access-Control-Allow-Origin' '*';
                     add_header 'Access-Control-Allow-Headers' 'Range';
                     add_header 'Access-Control-Max-Age' 1728000;
                     add_header 'Content-Type' 'text/plain charset=UTF-8';
                     add_header 'Content-Length' 0;
                        return 204;
                }
            }

            include snippets/phpmyadmin.conf;
            ssl on;
            ssl_certificate /etc/ssl/certs/iolab_sk.pem;
            ssl_certificate_key /etc/ssl/private/www.iolab.sk.key;
       }  
```
4. also in tne ```nginx.conf``` file add following rtmp section to stream video from camera using rtmp:
```
rtmp {
    server {
        listen 1935; # port listening to incoming stream
        chunk_size 2048; # Maximum chunk size for stream multiplexing. Default is 4096.

        # This application is for splitting the stream into HLS fragments
        application hls {
            live on; # Allows live input from above
            hls on; # Enable HTTP Live Streaming
            hls_type live; # Either 'event' or 'live' (live means played from current live position)
            deny play all; # Disable consuming the stream from nginx as rtmp

            hls_fragment 2s;
            hls_playlist_length 10s;

            # Pointing this to an SSD is better as this involves lots of IO
            hls_path /tmp/hls/;
            
            # Instruct clients to adjust resolution according to bandwidth
            hls_variant _subsd BANDWIDTH=400000;
            #hls_variant _sd BANDWIDTH=1000000;
            #hls_variant _hd BANDWIDTH=5000000;
        }
    }
}
```
5. restart nginx server with running ```sudo systemctl restart nginx``` command
