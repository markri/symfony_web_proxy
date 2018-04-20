# Symfony web proxy #

This is a symfony setup of a simple web proxy. It can be hosted under your own domain, all
 requests must go through the symfony security layer which is protected by the standard
 symfony authentication / authorization layer. After logging in, every request with all
 headers, cookies, post and get params is forwarded to a configured URL in the .env file.


## Why?

I needed to make an insecure and oudated PHP application secure again. No way I would make
my hands dirty by diving into the big bowl of spaghetti. So I wrapped the application by
creating this proxy, and let the authentication be handled in a trustworthy way. The actual
authentication is still using the original data from database, so the user management in
the old application is still working.

Afther authentication is done, every request is forwarded with Guzzle (with the acquired
session cookie and a header['host'] rewrite).


## Get started

Fire up the docker stack, install vendors and add a host entry and you're good to go!

    docker-compose up -d
    docker exec -ti wrap_phpcli composer install
    docker exec -ti wrap_phpcli bin/console doctrine:schema:create
    docker exec -ti wrap_mariadb mysql -u wrapper --password=wrapper -e "INSERT INTO users (username, password, active) VALUES ('admin', '21232f297a57a5a743894a0e4a801fc3', 1);" wrapper
    echo "127.0.0.1 application.local" >> /etc/hosts

Now you can open up your browser and go to http://application.local and login with admin:admin


## Caveat

As you dig in the security.yaml, you'll find a single md5 hashing mechanism (to adapt to
the old application). Needless to say that this weak hashing is now considered insecure.

Be sure that the MD5 hash is stored lowercase in database! Otherwise you'll get an invalid
credentials message on login.
