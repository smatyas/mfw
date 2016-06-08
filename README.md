MFW - a simple PHP microframework
=================================

[![Build Status](https://travis-ci.org/smatyas/mfw.svg?branch=master)](https://travis-ci.org/smatyas/mfw)
[![Coverage Status](https://coveralls.io/repos/github/smatyas/mfw/badge.svg?branch=master)](https://coveralls.io/github/smatyas/mfw?branch=master)

This repository contains a minimal MVC PHP framework and an POC application built on top of it.
The framework code itself is under the `lib` directory.
The application code is under the `src` directory.

The framework consists of the following custom main parts:
  - container
  - custom error handler (disabled by default, you can enable it in `web/index.php`)
  - ORM
  - routing
  - security
  - templating
  
The main entry point of the POC application is the `web/index.php`.
The framework configuration and the application setup in in this file.

This repository contains a docker environment setup for testing purposes, 
so you can easily try out and play around with the app.
The requests will hit a load balancer that dispatches them to two workers in round-robin.
Both workers are connected to the same memcached and mysql instance. 
The session handling is transparent (using memcached).

Running and testing
===================

1. Clone the repository
2. Build the development environment:

    ```
    docker-compose -f docker/docker-compose.yml build
    ```

3. Start the development environment:

    ```
    docker-compose -f docker/docker-compose.yml up -d
    ```
    
    It will spin up the following containers:
     - docker_web-lb - an nginx load balancer
     - docker_web-1 - nginx web worker #1
     - docker_web-2 - nginx web worker #2
     - docker_php-fpm-1 - PHP worker #1
     - docker_php-fpm-2 - PHP worker #2
     - memcached - a memcached server
     - mysql - a MySQL database server
    
4. Log in into one of the `php-fpm` containers:

    ```
    docker exec -it docker_php-fpm-1_1 /bin/bash
    ```
    
    (The project is mounted into the `/usr/share/nginx/html` directory, where you will be initially dropped.)
   
5. Install the project dependencies:

    ```
    composer install
    ```
   
6. Load the fixtures to fill the DB with some initial data:

    ```
    ./scripts/init_db.sh
    ```
    
    It imports a db dump to the database.
    The following users will be created (username / password - role):
      - user1 / user1 - PAGE_1
      - user2 / user2 - PAGE_2
      - user3 / user3 - PAGE_1 and PAGE_2
    
7. The site is now available at the port `8000` on your docker host address, i.e.: http://192.168.99.100:8000/

8. You can run the tests:

    ```
    vendor/bin/phpunit
    ```
    
    The code coverage report is accessible at port `8100` on your host address, i.e. http://192.168.99.100:8100/

9. Test the code style:

    ```
    vendor/bin/phpcs --standard=PSR2 lib/ src/ tests/ web/
    ```

10. Checking sent mails (if the custom error handler is enabled in `web/index.php`)

    Due to mail sending issues from docker, emails are actually not sent. 
    The php is configured to use a custom mail sending script: `docker/php-fpm/phpsendmail`
    The script writes all mail into `/tmp/phpsendmail.log`, so you can easily monitor them using the following command:
    
    ```
    tail -f /tmp/phpsendmail.log
    ```
    
    Remember that there are 2 PHP workers running, so you might want to monitor both of them.
