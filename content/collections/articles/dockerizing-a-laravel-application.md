---
id: 933c8c7b-5d3e-4ee2-b213-cb4117fda29f
blueprint: article
title: 'Dockerizing a Laravel Octane application'
introduction: "Deploying a Laravel application can be difficult for those who don't have experience with PHP applications. Docker is the solution to easily publish and let other people deploy your application without them needing to learn how it is exactly setup. In this article I will show how I've Dockerized Vigilant."
author: 1
updated_by: 1
updated_at: 1725297418
published_at: '2024-09-02'
content:
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: Introduction
  -
    type: paragraph
    content:
      -
        type: text
        text: 'The traditional setup process can be error-prone, requiring configuration in multiple applications (nginx, php, mysql, redis). This is not good if you want other people, who may not be familiar with PHP applications, to host your app. For example, what if you add a dependency in a later version of your application? With a traditional setup, everyone upgrading must remember to install that dependency. '
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Docker can simplify this process by packaging your entire application with all its dependencies into a single image. Think of it like a really small Linux instance that you get to configure. This way you are confident that it will run exactly the same on all machines. '
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Another advantage is scalability. If your application starts to struggle to keep up with all the HTTP requests coming in, you can spin up another container and load balance between the two. Or, if you need more queue workers, you can spin up a second container to do that."'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'You can extend that with Docker compose to add all the services you need such as MySQL and Redis.'
  -
    type: blockquote
    content:
      -
        type: paragraph
        content:
          -
            type: text
            marks:
              -
                type: bold
            text: 'Disclaimer:'
          -
            type: text
            text: " These are the steps I've taken to Dockerize Vigilant. Every application is different and may have its own unique dependencies. While not all steps are required for every application, others may need to add additional steps to address specific requirements. If you encounter any issues or have questions, feel free to join the Discord—I'd love to help!"
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: Requirements
  -
    type: paragraph
    content:
      -
        type: text
        text: 'To Dockerize a Laravel application you need the following:'
  -
    type: bulletList
    content:
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'A working Laravel application'
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'Docker with compose installed'
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: 'The Application'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'The application we are dockerizing in this article is '
      -
        type: text
        marks:
          -
            type: link
            attrs:
              href: 'https://github.com/govigilant/vigilant'
              rel: null
              target: null
              title: null
        text: Vigilant
      -
        type: text
        text: '. A monitoring tool for websites and webapplication that monitors more than just uptime.'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'It relies heavily on '
      -
        type: text
        marks:
          -
            type: link
            attrs:
              href: 'https://horizon.laravel.com'
              rel: null
              target: null
              title: null
        text: 'Laravel Horizon'
      -
        type: text
        text: " for all it's queues which is why we'll also be adding a seperate container just for running Horizon which we can easily scale if we need more processes that are handling our jobs."
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Vigilant also runs Google Lighthouse which requires Chrome to be installed.'
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: 'The Dockerfile'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'The Dockerfile contains the build process of the image. Here each step is defined to build the container.'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Vigilant uses Laravel Octane with frankenphp, '
      -
        type: text
        marks:
          -
            type: link
            attrs:
              href: 'https://hub.docker.com/r/dunglas/frankenphp'
              rel: null
              target: null
              title: null
        text: 'frankenphp provides a base image'
      -
        type: text
        text: ' which we can use. To keep the image lightweight we are going to use the Alpine build. To get started create a new `Dockerfile` in the root of your Laravel project and start with a base image using the `FROM` command.'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: 'FROM dunglas/frankenphp:latest-php8.3-alpine'
  -
    type: paragraph
    content:
      -
        type: text
        text: "After that we'll add packages that we need. I always find these through trial and error; when building the application, you might encounter a dependency error. With experience one will start to recognize these errors and immediately know how to fix them. Use the "
      -
        type: text
        marks:
          -
            type: link
            attrs:
              href: 'https://pkgs.alpinelinux.org/packages'
              rel: null
              target: null
              title: null
        text: 'Alpine packages'
      -
        type: text
        text: ' site to find the package that you need.'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          # Dependencies
          RUN apk add --no-cache bash git linux-headers libzip-dev libxml2-dev supervisor nodejs npm chromium

          # Composer
          RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

          # PHP extentions
          RUN docker-php-ext-install pdo pdo_mysql sockets pcntl zip exif bcmath

          # Redis
          RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
                && pecl install redis \
                && docker-php-ext-enable redis \
                && apk del pcre-dev ${PHPIZE_DEPS} \
                && rm -rf /tmp/pear
  -
    type: paragraph
    content:
      -
        type: text
        text: 'At this point we have a base container but our application is missing.  Using '
      -
        type: text
        marks:
          -
            type: code
        text: COPY
      -
        type: text
        text: " we can copy the entire application into the image. After that we'll set the working directory of the container using "
      -
        type: text
        marks:
          -
            type: code
        text: WORKDIR
      -
        type: text
        text: .
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          COPY . /app
          WORKDIR /app
  -
    type: paragraph
    content:
      -
        type: text
        text: "We want to add all of our application's dependencies to the container, build our frontend assets, and add those to the container as well."
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          ENV COMPOSER_ALLOW_SUPERUSER=1
          RUN composer install --no-dev --prefer-dist --no-interaction

          RUN npm install
          RUN npm run build
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Vigilant has to be able to run Google Lighthouse, so we will install it globally in the container.'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          RUN npm install -g lighthouse
          RUN npm install -g @lhci/cli@0.7.x
  -
    type: paragraph
    content:
      -
        type: text
        text: 'We also need to get the Octane binary and we set the '
      -
        type: text
        marks:
          -
            type: code
        text: OCTANE_SERVER
      -
        type: text
        text: ' env variable:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          RUN yes | php artisan octane:install --server=frankenphp
          ENV OCTANE_SERVER=frankenphp
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Finally, we set the location of the crontab, the '
      -
        type: text
        marks:
          -
            type: code
        text: CHROME_PATH
      -
        type: text
        text: ' environment variable for Lighthouse, and the entrypoint of the container.'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          RUN /usr/bin/crontab /app/docker/crontab

          ENV CHROME_PATH=/usr/bin/chromium

          ENTRYPOINT ["sh", "/app/docker/entrypoint.sh"]
  -
    type: paragraph
    content:
      -
        type: text
        text: 'The entrypoint is the command that the container runs at startup. As you can see, we run a script here. The script looks like this:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |
          #!/bin/sh

          # Make sure all required Laravel dirs exist
          mkdir -p /app/storage/framework/cache
          mkdir -p /app/storage/framework/sessions
          mkdir -p /app/storage/framework/views

          # Generate a key if we do not have one
          if ! grep -q "^APP_KEY=" ".env" || [ -z "$(grep "^APP_KEY=" ".env" | cut -d '=' -f2)" ]; then
              php artisan key:generate
          fi

          # Run migrations
          php artisan migrate --force

          # Start supervisor
          /usr/bin/supervisord -c /app/docker/supervisor/supervisor.conf
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Supervisor then does two things: it runs the cron and Octane. The supervisor configuration looks like this:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          [supervisord]
          nodaemon=true

          [program:cron]
          command=/usr/sbin/crond -f -l 8
          stdout_logfile=/dev/stdout
          stderr_logfile=/dev/stderr
          stdout_logfile_maxbytes=0
          stderr_logfile_maxbytes=0
          autorestart=true

          [program:octane]
          command=php artisan octane:frankenphp
          stdout_logfile=/dev/stdout
          stderr_logfile=/dev/stderr
          stdout_logfile_maxbytes=0
          stderr_logfile_maxbytes=0
          autorestart=true
  -
    type: paragraph
    content:
      -
        type: text
        text: 'For our cron we also need to copy the crontab into the container using '
      -
        type: text
        marks:
          -
            type: code
        text: 'RUN /usr/bin/crontab /app/docker/crontab'
      -
        type: text
        text: ' in the dockerfile.'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'The crontab looks like this:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: '* * * * * php /app/artisan schedule:run'
  -
    type: heading
    attrs:
      level: 3
    content:
      -
        type: text
        text: 'Building the container'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'With the '
      -
        type: text
        marks:
          -
            type: code
        text: 'docker build'
      -
        type: text
        text: ' command we can build the docker file. The '
      -
        type: text
        marks:
          -
            type: code
        text: '-t'
      -
        type: text
        text: ' option provides a tag and the dot at the end is the directory where the Dockerfile is located.'
  -
    type: paragraph
    content:
      -
        type: text
        marks:
          -
            type: code
        text: 'docker build -t vigilant .'
  -
    type: heading
    attrs:
      level: 3
    content:
      -
        type: text
        text: 'Persisting the public folder'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'In our compose file, we have bound the '
      -
        type: text
        marks:
          -
            type: code
        text: public
      -
        type: text
        text: " directory to a Docker volume so that it is persistent. But when we do that new files from our repository will not be picked up. In order to solve this we'll add a step to our "
      -
        type: text
        marks:
          -
            type: code
        text: Dockerfile
      -
        type: text
        text: ' and a step to our entrypoint. '
  -
    type: paragraph
    content:
      -
        type: text
        text: 'In our '
      -
        type: text
        marks:
          -
            type: code
        text: Dockerfile
      -
        type: text
        text: ' ,we will copy the content of the '
      -
        type: text
        marks:
          -
            type: code
        text: public
      -
        type: text
        text: ' directory to a temporary folder:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          RUN mkdir /tmp/public/
          RUN cp -r /app/public/* /tmp/public/
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Each time the container starts we can then copy those files to the '
      -
        type: text
        marks:
          -
            type: code
        text: public
      -
        type: text
        text: ' folder so that they get created on our mounted volume.'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: 'cp -f -r /tmp/public/* /app/public'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'The only downside is that deleted files from your repository will not be deleted in the volume.'
  -
    type: paragraph
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: 'Putting it all together'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'We currently have built an image for our Laravel application but we are missing a few key components such as a database. We can use Docker compose to create a single file wich defines what our application needs to run'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Docker Compose is a tool that simplifies the management of multi-container Docker applications. With Docker Compose, you can define and orchestrate multiple services (such as web servers, databases, and queue runners) within a single YAML configuration file. This file, typically named '
      -
        type: text
        marks:
          -
            type: code
        text: docker-compose.yml
      -
        type: text
        text: ', allows you to specify the services your application needs, along with their configurations, dependencies, and networking.'
      -
        type: hardBreak
      -
        type: text
        text: "To setup your application you will the only need this compose file to start the entire stack with all it's dependencies. "
  -
    type: paragraph
    content:
      -
        type: text
        text: "Let's start by creating the "
      -
        type: text
        marks:
          -
            type: code
        text: docker-compose.yml
      -
        type: text
        text: ' in the root of the project and adding our application.'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          services:
              app:
                  build:
                      context: .
                  volumes:
                      - type: bind
                        source: ./.env
                        target: /app/.env
                      - ./storage:/app/storage
                      - public:/app/public
                  restart: always
                  working_dir: /app

          volumes:
              public:
  -
    type: paragraph
    content:
      -
        type: text
        text: 'We define a service called '
      -
        type: text
        marks:
          -
            type: code
        text: app
      -
        type: text
        text: ' and tell Docker compose that the Dockerfile to build the image is located in the same directory as our compose file. Then we bind three volumes:'
  -
    type: bulletList
    content:
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'Environment variable'
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'Storage and public folders for persisting files'
  -
    type: paragraph
    content:
      -
        type: text
        text: "We tell compose that we always want to restart the container if it stops and we'll set the working directory."
  -
    type: paragraph
    content:
      -
        type: text
        text: 'If we start it up like this, using '
      -
        type: text
        marks:
          -
            type: code
        text: 'docker compose up'
      -
        type: text
        text: ' in our project directory, we will see that compose will build the image and start running our entrypoint script. Our entrypoint will then start Octane and cron.'
  -
    type: paragraph
    content:
      -
        type: text
        text: "But how do we access it? Right now we can't! That is because we did not bind any ports. We have to define what containers and what ports it should open. An alternative for this is to use something like "
      -
        type: text
        marks:
          -
            type: link
            attrs:
              href: 'https://traefik.io/traefik/'
              rel: null
              target: null
              title: null
        text: Traefik
      -
        type: text
        text: ' which is a reverse proxy. With Traefik you can add labels to your compose file to tell it on what URL the service should be accessible without configuring ports for each service. '
  -
    type: paragraph
    content:
      -
        type: text
        text: 'But for now we will just open the Octane port:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          services:
              app:
                  build:
                      context: .
                  volumes:
                      - type: bind
                        source: ./.env
                        target: /app/.env
                      - ./storage:/app/storage
                      - public:/app/public
                  restart: always
                  working_dir: /app
                  ports:
                      - "8000:8000"

          volumes:
              public:
  -
    type: paragraph
    content:
      -
        type: text
        text: "When we run the container now we see that it is accessible on port 8000, great! But our application still is missing the services it needs. Let's add a database and redis container."
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          services:
              app:
                  build:
                      context: .
                  volumes:
                      - type: bind
                        source: ./.env
                        target: /app/.env
                      - ./storage:/app/storage
                      - public:/app/public
                  restart: always
                  working_dir: /app
                  networks:
                      - vigilant
                  healthcheck:
                      test: curl --fail http://localhost || exit 1
                      interval: 30s
                      timeout: 10s
                      retries: 5
                  ports:
                      - "8000:8000"

              mysql:
                  image: mysql:8.0
                  restart: always
                  environment:
                      - MYSQL_DATABASE=vigilant
                      - MYSQL_ROOT_PASSWORD=password
                  volumes:
                      - database:/var/lib/mysql
                  networks:
                      - vigilant

              redis:
                  image: redis:7
                  restart: always
                  volumes:
                      - redis:/data
                  networks:
                      - vigilant

          networks:
              vigilant:

          volumes:
              public:
              database:
              redis:
  -
    type: paragraph
    content:
      -
        type: text
        text: "As you can see, I've also created a network so that I have some control over which containers can talk to each other. This also means that we do not have to open ports. But how does our application container connect to MySQL and Redis? We use the container name! In our environment file which we have bound to the application container:"
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          DB_CONNECTION=mysql
          DB_HOST=database
          DB_PORT=3306

          REDIS_HOST=reds
          REDIS_PORT=6379
  -
    type: paragraph
    content:
      -
        type: text
        text: "When we'll startup the containers using "
      -
        type: text
        marks:
          -
            type: code
        text: 'docker compose up'
      -
        type: text
        text: " now we see that they all start to run and be able to connect to eachother. One final piece is missing which is Horizon. We could add it to the supervisor but then when we scale we are forced to scale the Octane server and Horizon. Ideally we'd be able to spin up a second container just for Horizon. So that is what we'll do by overwriting  the entrypoint in our compose file:"
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          services:
              ...
              horizon:
                  build:
                      context: .
                  volumes:
                      - type: bind
                        source: ./.env
                        target: /app/.env
                        read_only: true
                      - ./storage:/app/storage
                      - public:/app/public
                  restart: always
                  working_dir: /app
                  networks:
                      - vigilant
                  entrypoint: ["php", "artisan", "horizon"]
  -
    type: paragraph
    content:
      -
        type: text
        text: 'This is great, we can use '
      -
        type: text
        marks:
          -
            type: code
        text: 'docker-compose scale horizon=3'
      -
        type: text
        text: ' to multiply the Horizon containers to get more processing power!'
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: 'Healthchecks and startup order'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'You may have noticed that your application container can start before your database. Or that Horizon may start before Redis is up which will give errors. It would also be nice know if the container is healthy. We can combine these two things fairly easily by adding health checks and dependencies to our compose file:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          services:
              app:
                  build:
                      context: .
                  volumes:
                      - type: bind
                        source: ./.env
                        target: /app/.env
                      - ./storage:/app/storage
                      - public:/app/public
                  restart: always
                  working_dir: /app
                  networks:
                      - vigilant
                  healthcheck:
                      test: curl --fail http://localhost || exit 1
                      interval: 30s
                      timeout: 10s
                      retries: 5
                  depends_on:
                      mysql:
                          condition: service_healthy
                  ports:
                      - "8000:8000"

              horizon:
                  build:
                      context: .
                  volumes:
                      - type: bind
                        source: ./.env
                        target: /app/.env
                        read_only: true
                      - ./storage:/app/storage
                      - public:/app/public
                  restart: always
                  working_dir: /app
                  networks:
                      - vigilant
                  entrypoint: ["php", "artisan", "horizon"]
                  depends_on:
                      mysql:
                          condition: service_healthy
                      redis:
                          condition: service_healthy

              mysql:
                  image: mysql:8.0
                  restart: always
                  environment:
                      - MYSQL_DATABASE=vigilant
                      - MYSQL_ROOT_PASSWORD=password
                  volumes:
                      - database:/var/lib/mysql
                  networks:
                      - vigilant
                  healthcheck:
                      test: ["CMD", "mysqladmin" ,"ping", "-h", "localhost"]
                      interval: 10s
                      timeout: 20s
                      retries: 10

              redis:
                  image: redis:7
                  volumes:
                      - redis:/data
                  networks:
                      - vigilant
                  healthcheck:
                      test: [ "CMD", "redis-cli", "ping" ]

          networks:
              vigilant:

          volumes:
              public:
              database:
              redis:
  -
    type: paragraph
    content:
      -
        type: text
        text: "As you can see we've added commands to check if Redis and MySql are working. Then on the app and Horizon containers we've added a "
      -
        type: text
        marks:
          -
            type: code
        text: depends_on
      -
        type: text
        text: '  which tells Docker compose to wait untill the services are healthy before starting. '
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: 'A note on deploying without Octane'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'When not using Octane, you need to replace it with PHP-FPM. You can then add a nginx container to serve the requests to FPM.'
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: Feedback
  -
    type: paragraph
    content:
      -
        type: text
        text: 'If you find any errors in this article, please report them on Discord (see the link in the footer) so that we can correct them!'
---
