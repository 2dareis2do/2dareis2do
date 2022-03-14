# Virtual Edit Link Field

When working in a de-coupled environment it might be useful to output a Drupal
entity's edit link as part of a RESTful implementation (such as when using 
Drupal's core JSON API that comes preinstalled on Drupal Distro such as 
contenta).

## Install

Download and install like any other Drupal Module. Consider installing via 
composer as is recommended by Drupal.

## Usage

There is pretty much no config required. Once enabled you should see the 
virtual `editLink` field output in the attributes array along with all other 
fields that are exposed to the REST API and other modules.

Timeout is currently set to 1 day (60s x 60 x 24).

e.g.

```  "attributes": {
                "internalId": 3,
                "isPublished": true,
                "title": "The umami guide to our favorite mushrooms",
                "createdAt": "2022-02-17T01:34:43+0100",
                "updatedAt": "2022-02-17T19:31:05+0100",
                "isPromoted": true,
                "path": "/articles/the-umami-guide-to-our-favourite-mushrooms",
                "editLink": "http://drupal.docker.localhost/node/3/edit",```

## Todo 

Tests