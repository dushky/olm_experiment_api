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
1. run ```sudo apt -y update``` and ```sudo apt -y install uild-essential libevent-dev libjpeg-dev libbsd-dev``` to install dependencies
2. in the home directory (```cd ~```) clone project of ustreamer library with command ```git clone –depth=1
   https://github.com/pikvm/ustreamer```
3. then go to the downloaded directory with ```cd ustreamer``` and install library with ```make``` command
4. to `nginx.conf` http section add following to allow stream video via ssl secured port 9000:
```
server {
    listen 9000;
    location /stream {
        postpone_output 0;
        proxy_buffering off;
        proxy_ignore_headers X-Accel-Buffering;
        proxy_pass http://0.0.0.0:9001;
    }
    ssl on;
    ssl_certificate <absolutna cesta ku .pem certifikatu>;
    ssl_certificate_key <aboslutna cesta ku .key klucu certifikatu>;
} 
```
5. restart nginx server with running ```sudo systemctl restart nginx``` command
6. in the application set port of the connected camera for device in "Device" section
