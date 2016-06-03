MFW - a simple microframework
=============================

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
   
7. Load the fixtures to fill the DB with some initial data:

    TODO
    
8. The site is now available at the port `8000` on your docker host address, i.e.: http://192.168.99.100:8000/

9. You can run the tests:

    ```
    vendor/bin/phpunit
    ```

10. Test the code style:

    ```
    vendor/bin/phpcs --standard=PSR2 lib/ src/ tests/ web/
    ```
