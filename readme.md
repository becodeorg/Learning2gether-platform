# Learning2Gether
This repository contains the e-learning platform "Learning2Gether".
This platform will be built on the open source Symfony Framework.

This readme contains installation instructions and documentation links.

## Contributers
This project was created during our training at [Becode](http://becode.org).
The main contributers were, in no specific order:
- @Anastasiyavyp	Anastasiya Vyprytska
- @bona-kim	Bona    Bona Kim
- @janvdv96	        Jan Van de Velde
- @joostvannieu	    Joost Vannieuwenhuyse
- @JosephLindzius	Joseph Lindzius
- @Nicnicsai	    Nicole Reyes Guttmann
- @Timmeahj	        Tim Broos
- @Lisonallie		Allison Van Linden
	
We received technical support from our coach @grubolsch (Koen Eelen).

## How to install
The vhost for this project is more complex than a simple PHP script, and is based on the Symfony Vhost style.
This configuration assumes that your site will be called **l2g.local** and use the directory **/var/www/l2g-platform/**. If you use something different you will have to adapt the vhost configuration.

Please be aware that in this project the root directory for PHP will be public/. All your public available resources should be available from this directory (eg.: public/css).

```apacheconfig
<VirtualHost *:80>
	ServerName l2g.local

	DocumentRoot /var/www/l2g-platform/public
        DirectoryIndex /index.php

        <Directory /var/www/l2g-platform/public>
        	AllowOverride All
        	Order Allow,Deny
       		Allow from All
       
		FallbackResource /index.php
	 </Directory>

	ErrorLog ${APACHE_LOG_DIR}/error-l2g.log
	CustomLog ${APACHE_LOG_DIR}/access-l2g.log combined
</VirtualHost>
```

### Installing the project
After cloning this repo, you have to run `composer install` inside the root directory. You might need to install [Composer](https://getcomposer.org/download/) to do this.

### Creating the database
Inside the `.env` file you can change the `DATABASE_URL` parameter with your database configuration. Make sure to never commit this file!

### Installing the database.
Make sure you create a new database `l2g`.

The run the following command in the project root:
`php bin/console doctrine:migrations:migrate`

## Documentation
- [Logos](https://drive.google.com/open?id=1vpV13Va6My1ITQnwXOLbVLUsE1hiwB2x)
- [Summary of the features required](https://docs.google.com/document/d/1Zps_QZvev8AFjrzgFvTFNxEYxrnZHyXtU2FgkRBko6U/edit?usp=sharing)
- [Symfony documentation](https://symfony.com/doc/current/index.html)
- [The symfony maker bundle](https://symfony.com/doc/current/bundles/SymfonyMakerBundle/index.html)