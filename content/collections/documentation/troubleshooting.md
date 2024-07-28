---
id: 6b9d443b-81cd-4161-9fc7-df698dbfcab6
blueprint: documentation
title: Troubleshooting
template: documentation/show
updated_by: 1
updated_at: 1722157907
type: hosting
content:
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'Vigilant is currently under development which means that there are probably some bugs in the code. This can mean that data is missing from your frontend. When this happens please take a look at the log files and/or the job queue.'
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'If you encounter an issue it would greatly be appreciated if you post it to the '
      -
        type: text
        marks:
          -
            type: link
            attrs:
              href: 'https://github.com/govigilant/vigilant/issues'
              rel: null
              target: null
              title: null
        text: 'GitHub issues'
      -
        type: text
        text: .
  -
    type: heading
    attrs:
      textAlign: left
      level: 3
    content:
      -
        type: text
        text: Docker
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'First of all confirm that the containers are running and are healthy using '
      -
        type: text
        marks:
          -
            type: code
        text: 'docker ps'
      -
        type: text
        text: '. '
      -
        type: hardBreak
      -
        type: text
        text: 'If you see any issues go to the folder where the compose file is located and examine the logs: '
      -
        type: text
        marks:
          -
            type: code
        text: 'docker compose logs -f'
  -
    type: heading
    attrs:
      textAlign: left
      level: 3
    content:
      -
        type: text
        text: Logs
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: "Vigilant's logs are stored in the "
      -
        type: text
        marks:
          -
            type: code
        text: storage/logs
      -
        type: text
        text: ' directory, all errors will be logged here.'
  -
    type: heading
    attrs:
      textAlign: left
      level: 3
    content:
      -
        type: text
        text: 'Job Queue'
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'Vigilant uses a job queue called '
      -
        type: text
        marks:
          -
            type: link
            attrs:
              href: 'https://laravel.com/docs/master/horizon'
              rel: null
              target: null
              title: null
        text: Horizon
      -
        type: text
        text: '. This is used for all of the different tasks that Vigilant runs. '
  -
    type: paragraph
    attrs:
      textAlign: left
    content:
      -
        type: text
        text: 'Horizon tracks what tasks/jobs have succeeded and failed. To access Horizon you can go to '
      -
        type: text
        marks:
          -
            type: code
        text: /horizon
      -
        type: text
        text: ' on your Vigilant instance.'
---
