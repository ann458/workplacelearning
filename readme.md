[![BCH compliance](https://bettercodehub.com/edge/badge/HUInstituteForICT/workplacelearning?branch=master&token=83dd337c2c87d86fa2fe2cde55c50f308c1291d4)](https://bettercodehub.com/)

## Config




## Unsure value of this documentation. 
In deze webfolder (/sites/werkplekleren.hu.nl/) bevinden zich 3 mappen.

htdocs/   bevat de uitrol van 'Project Werkplekleren (25-08-2016)'
laravel/  bevat de oorspronkelijke installatie van laravel.com
tmp/      bevat copies van beide




# VirtualHost *:80

DocumentRoot      /sites/werkplekleren.hu.nl/htdocs/public
Directory         /sites/werkplekleren.hu.nl/htdocs


### Docker
Edit the `host_lan_ip` in `docker-compose.yml` to reflect your local IP address to use xdebug