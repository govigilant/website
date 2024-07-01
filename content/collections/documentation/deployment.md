---
id: 1e96f1a9-d3a2-45f7-8007-de1bf6173957
blueprint: documentation
title: 'Self-Hosted Deployment'
template: documentation/show
updated_by: 1
updated_at: 1719852179
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
                      - .env.docker:/app/.env
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
        text: 'The application will be available on port 8000. '
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
---
