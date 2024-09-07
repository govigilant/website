---
id: 1e96f1a9-d3a2-45f7-8007-de1bf6173957
blueprint: documentation
title: 'Self-Hosted Deployment'
template: documentation/show
updated_by: 1
updated_at: 1725698889
type: hosting
content:
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'Vigilant can easily be deployed using Docker Compose.'
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'Use the following docker-compose file:'
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
                  image: ghcr.io/govigilant/vigilant:latest
                  volumes:
                      - .env:/app/.env
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
                  image: ghcr.io/govigilant/vigilant:latest
                  volumes:
                      - .env:/app/.env
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
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'Then create an environment file: '
      -
        type: text
        marks:
          -
            type: code
        text: 'touch .env'
      -
        type: text
        text: ' and add these variables:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          APP_KEY=
          APP_URL=http://vigilant.govigilant.io

          DB_CONNECTION=mysql
          DB_HOST=mysql
          DB_PORT=3306
          DB_DATABASE=vigilant
          DB_USERNAME=root
          DB_PASSWORD=password

          REDIS_HOST=redis
          REDIS_PORT=6379

          MAIL_MAILER=smtp
          MAIL_HOST=127.0.0.1
          MAIL_PORT=25
          MAIL_USERNAME=null
          MAIL_PASSWORD=null
          MAIL_ENCRYPTION=null
          MAIL_FROM_ADDRESS="notifications@govigilant.io"
          MAIL_FROM_NAME="${APP_NAME}"
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'Adjust your URL, if you plan on using e-mail notifications you can setup email here too.'
      -
        type: hardBreak
      -
        type: text
        text: 'Do not set the '
      -
        type: text
        marks:
          -
            type: code
        text: APP_KEY
      -
        type: text
        text: ', it will be generated when you first start the container.'
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'Start the application using '
      -
        type: text
        marks:
          -
            type: code
        text: 'docker-compose up'
      -
        type: text
        text: '. The application will be available on port 8000.'
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'After starting the container once you may optionally set the '
      -
        type: text
        marks:
          -
            type: code
        text: .env
      -
        type: text
        text: ' file to read only using:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |2-
                      - type: bind
                        source: ./.env
                        target: /app/.env
                        read_only: true
  -
    type: heading
    attrs:
      textAlign: left
      level: 3
    content:
      -
        type: text
        text: HTTPS
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'Use a reverse proxy such as '
      -
        type: text
        marks:
          -
            type: link
            attrs:
              href: 'https://nginx.org'
              rel: null
              target: _blank
              title: nginx
        text: nginx
      -
        type: text
        text: ' or '
      -
        type: text
        marks:
          -
            type: link
            attrs:
              href: 'https://traefik.io/traefik/'
              rel: null
              target: _blank
              title: Traefik
        text: Traefik
      -
        type: text
        text: ' to handle HTTPS. Example nginx configuration:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          server {
              listen 80;
              server_name vigilant.example.com;

              # Redirect all HTTP traffic to HTTPS
              return 301 https://$host$request_uri;
          }

          server {
              listen 443 ssl;
              server_name vigilant.example.com;

              # SSL certificate and key files
              ssl_certificate /path/to/your/cert/certpem;
              ssl_certificate_key /path/to/your/cert/priveate.pem;

              # SSL configuration
              ssl_protocols TLSv1.2 TLSv1.3;
              ssl_prefer_server_ciphers on;
              ssl_ciphers "EECDH+AESGCM:EDH+AESGCM:AES256+EECDH:AES256+EDH";

              location / {
                  proxy_pass http://127.0.0.1:8000;
                  proxy_set_header Host $host;
                  proxy_set_header X-Real-IP $remote_addr;
                  proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
                  proxy_set_header X-Forwarded-Proto $scheme;
              }
          }
  -
    type: heading
    attrs:
      textAlign: left
      level: 3
    content:
      -
        type: text
        text: 'Other Architectures'
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: "Currently Vigilant only supports Intel based CPU's. If you are for example on Apple silicon you have to build the image yourself. "
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'To do this, clone the git repository: '
      -
        type: text
        marks:
          -
            type: code
        text: 'git clone git@github.com:govigilant/vigilant.git'
      -
        type: text
        text: ' '
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'Create your env: '
      -
        type: text
        marks:
          -
            type: code
        text: 'cp .env.docker .env'
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'Replace the image with a build parameter in your '
      -
        type: text
        marks:
          -
            type: code
        text: docker-compose.yml
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |2-
              app:
          -        image: ghcr.io/govigilant/vigilant:latest
          +        build:
          +            context: .

          ...
              horizon:
          -        image: ghcr.io/govigilant/vigilant:latest
          +        build:
          +            context: .
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'Then run '
      -
        type: text
        marks:
          -
            type: code
        text: 'docker-compose up'
      -
        type: text
        text: ' '
---
