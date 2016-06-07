MFW - a simple microframework
=============================

[![Build Status](https://travis-ci.org/smatyas/mfw.svg?branch=master)](https://travis-ci.org/smatyas/mfw)
[![Coverage Status](https://coveralls.io/repos/github/smatyas/mfw/badge.svg?branch=master)](https://coveralls.io/github/smatyas/mfw?branch=master)

Development
===========

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
