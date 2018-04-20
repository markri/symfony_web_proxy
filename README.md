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

Fire up the docker stack, add a host entry and you're good to go!

    docker-compose up -d
    echo "127.0.0.1 application.local" >> /etc/hosts

Now you can open up your browser and go to http://application.local