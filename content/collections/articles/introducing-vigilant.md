---
id: 6062d2b1-6433-4f88-a129-0500e40ed007
blueprint: article
title: 'Introducing Vigilant'
introduction: |-
  Vigilant is an open source technical monitoring application for websites and web applications.
  It will monitor different aspects of your website in order to ensure that your site is running great. 
  When any of these monitors detect a change it will notify you through a flexible notification system.
author: 1
updated_by: 1
updated_at: 1717134061
published_at: '2024-05-20'
---
## The goal of Vigilant

Vigilant is an application that monitors the technical health of any website or web application.
Many aspects of a website change with every release, a simple mistake may not be immediatly visible.
Especially if it not directly visible on the page. For example, what if a server update increases the time that it responds? A simple uptime monitoring tool will not see the difference since the site will not be down but it would be great to be notified that this happened. 
Or what if an user adds an incorrectly sized image to the homepage which causes lighthouse scores to drop?


Vigilant works by running different tests. These go from simple such as pings to get the latency to actively browsing to your site and run [Google lighthouse](https://developer.chrome.com/docs/lighthouse/overview). By gathering this data over time we can notify when something changes.

## Open Source

Vigilant is proudly open source software. This means that its source code is publicly available for anyone to inspect, modify, and enhance. The main advantage is transparancy. By making Vigilant open source, I provide complete transparency into how the application works. Users can verify for themselves the integrity and functionality of the code and how data is handled. This transparency helps build trust, as you can see exactly what the application does and how it handles your data.

Open source software also often benefits from heightened security. With more eyes on the code, potential vulnerabilities can be identified and addressed more quickly. Everyone can audit the code, ensuring that security practices are up to date and robust. This collective scrutiny helps create a more secure and reliable project.


## Self Hostable

This project is self hostable, meaning that you can deploy it on your own server for full control of your data and infrastructure.
Vigilant uses Docker for deployments so that anyone can quickly set it up.


## (Planned) Features

Here is a quick list of features that I'd like to add. Some are more worked out than others.

### Notifications

This is the most important feature. I wanted notifications to be flexible and customizable. 
For example, it must be possible to set different conditions for different sites. It must be possible to route notification A for site A to Slack and for site B to E-mail. 

Vigilant controls when a notification is sent but you control when it may be sent. That might seem a bit vague but in practice it means that you can add conditions to notifications. A condition can be as simple as 'Only sent this notification for site X' or as complex as 'Only sent this notification when X number has increased by 20% in the 24 hours'.

### Uptime

Uptime monitoring isn't the primary goal of Vigilant but it is a nice addition in my opinion. It was also the first monitoring feature that I've added because it seemed easy to add.
It is also a good way to get basic latency information from Vigilant's server to your site.


### Lighthouse

Lighthouse is a good indicator of how your site is performing, it contains a lot of checks and it's main scores are easy to understand.
An added bonus of lighthouse is that it uses a real browser to perform the test this means that it outputs lots of data.

### Broken links

Crawling your site and finding links that do not return a 2xx or 3xx status code.

### DNS

DNS changes can be catastrophic, this is why I think that it is a good idea to monitor them and get notified of changes. Even if you applied the change itself, I think a notification would be good.

### HTTPS

All sites should run on HTTPS, the monitoring would check the following:
- Expiry of the certificate
- Expiry of the root certificate
- Certificate changes
- Domain equals the one on the certificate


### Other features

There are a few other features that I've not worked out but may like to add:

- Cron monitoring
- Web Vitals
- Response times for specific URL's
- Mixed Content


## Why create another monitoring tool?

There are many other monitor tools which do something similar, most of them not open source.
When looking at open source options there are some amazing projects such as [Uptime Kuma](https://github.com/louislam/uptime-kuma) and [Sitespeed.io](https://github.com/sitespeedio/sitespeed.io).
These tools do one thing very good but I want a single tool that does these things with an advanced notification system.

So to answer the question, I want an open source tool that combines other tools. I couldn't find one so I started developing my own. It is also my way of giving back to the awesome selfhosted / open source community of which I have already taken so much.
Time will tell if we need another monitoring tool and if Vigilant gets any users but I believe that Vigilant will add enough value once all features are implemented for people to start using it.

## How to stay updated

On the [homepage](/) there is a place where you can leave your email address to stay updated on Vigilant's latest developments