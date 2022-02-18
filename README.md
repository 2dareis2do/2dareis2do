# Virtual Edit Link Field

When working in a de-coupled environment it might be useful to output a Drupal
entitie's edit link as part of a RESTful implementation (such as when usimg 
Drupal's core JSON API that comes preinstalled on Drupal Distro such as 
contenta).

## Install

Download and install like any other Drupal Module. Consider installing via 
composer as is recommended by Drupal.

## Usage

There is pretty much no config required. Once enabled you should see the 
virtual_edit_link_field output with all other fields that are exposed to the 
REST API and other modules.
