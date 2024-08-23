---
id: d844afd5-9310-4622-9859-8763aa1f484e
blueprint: article
title: 'It is now possible to self-host Vigilant!'
introduction: "The past few months I've worked on getting Vigilant stable and self-hostable. The application now has three monitoring features and is ready to be self hosted using Docker. This means that everyone can start playing with Vigilant!"
author: 1
updated_by: 1
updated_at: 1724441770
content:
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Vigilant is an open source monitoring tool for websites that is currently in development. It monitors more than just uptime and response times, see the '
      -
        type: text
        marks:
          -
            type: link
            attrs:
              href: 'statamic://entry::6062d2b1-6433-4f88-a129-0500e40ed007'
              rel: null
              target: null
              title: null
        text: 'introduction post'
      -
        type: text
        text: ' on what Vigilant monitors.'
  -
    type: paragraph
    content:
      -
        type: text
        text: |-
          Since that post I've been busy with making sure that Vigilant will be usable as an alpha version.
          The goal is that in this version it should run stable but I am sure that there are small bugs in the application.
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: 'New Feature: DNS Monitoring'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'DNS is something that mostly goes untouched, which is good. A small change in a DNS record can be catastrophic. This is the reason to add a DNS monitoring feature into Vigilant. It is easy and quick to setup, just enter a domain name and choose the records that you want to monitor.Vigilant will periodically check the records and notify you when they change.'
  -
    type: set
    attrs:
      id: m07410fb
      values:
        type: full_width_image
        image: dns-monitor.png
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: 'New Feature: Lighthouse Monitoring'
  -
    type: paragraph
    content:
      -
        type: text
        text: |-
          When actively developing a website it can quickly go unnoticed that the lighthouse scores have dropped. Maybe someone uploaded a huge image or you add a huge library.
          Lighthouse provides a lot of data, Vigilant now uses a small portion of it to notify you.
  -
    type: set
    attrs:
      id: m0742ycf
      values:
        type: full_width_image
        image: lighthouse-monitoring-1.png
  -
    type: set
    attrs:
      id: m0742zpi
      values:
        type: full_width_image
        image: lighthouse-monitoring-2.png
  -
    type: paragraph
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: 'New Feature: Notifications'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Vigilant now has a built in notification system where you can finely control when and how notifications are sent. For example you can send a lighthouse notification if one of the scores changes by 10 percent. '
  -
    type: set
    attrs:
      id: m05hln9g
      values:
        type: full_width_image
        image: lighthouse-notification.png
  -
    type: paragraph
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Currently only '
      -
        type: text
        marks:
          -
            type: link
            attrs:
              href: ntfy.sh
              rel: null
              target: _blank
              title: null
        text: Ntfy
      -
        type: text
        text: ' is supported.'
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: 'Self Hostable'
  -
    type: paragraph
    content:
      -
        type: text
        text: |-
          Vigilant is now fully self hostable using Docker! This means that everyone with Docker installed can setup and play around with Vigilant.
          Head over to the 
      -
        type: text
        marks:
          -
            type: link
            attrs:
              href: 'statamic://entry::1e96f1a9-d3a2-45f7-8007-de1bf6173957'
              rel: null
              target: _blank
              title: null
        text: 'docs to see how to set this up'
      -
        type: text
        text: .
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: 'Next steps'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'The next step is currently to fix any new issues that I encounter. The docs also need love and I want to start sharing this project online to hopefully get some feedback.'
---
