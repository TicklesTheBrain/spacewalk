# Spacewalk

A puzzle hunt using RasPi's as webservers

### Overview

The idea is the following:

* Multiple Raspis, setup in different locations, setup as closed network wifi APs
* APs are passwordless, but have a captive portal that leads to the initial website/puzzle
* Solve the puzzle to get a phrase that you use as URL to get to the next website/puzzle
* Solve the last puzzle to get the guestbook website, sign your name and get directions to the next stage/raspi

Features:

* Some simple AF dynamic website to help things along - search website, guestbook website, help website with hints
* Using GPIO to determine the setup of the raspi (which websites and data to serve for different stages OR serve everything for dev/debugging)

Caveats:

* URLs have to use one of the standard TLDs otherwise browsers get upset
* As a result it is recommended to use the same TLD for all the website in one stage, state it clearly and explicitly in the captive portal & help page

Tech stack:
* `lnxrouter` script to setup the AP, which uses `hostapd` and `dnsmasq`
* `dnsmasq` resolves ALL urls to the gateway itself
* `nodogsplash` for captive portal functionality
* `wiringpi` to get state from GPIO
* `nginx` for webserver and URL setup. No additional setup needed for new websites since all URLs are resolved to the gateway anyway.
* `php8.1-fpm` to process php
* bash script called `swsetup` to do all the copying around and starting services

### Starting from scratch

Here I am just going to put some prerequisites so they don't get lost. Here is step-by-step procedure, I did when setting up this thing on a fresh raspbian (might work differently on different distros)

* First let's make sure our shit is updated

        sudo apt-get update & sudo apt-get ugprade to get all the updates

* Get git so we can pull repos and stuff 
* A directory at usr/share/spacewalk pulling newest git content from a public repo (this is the repo where this file lives). Use `mkdir`
* need a symlink to usr/bin to make the commands runnable easier

        sudo ln -s /usr/share/spacewalk /usr/bin
        sudo ln -s /usr/share/spacewalk/lnxrouter /usr/bin/lnxrouter

* lnxrouter script is just copied into this repo, with one modification: I've added dnsmasq property to resolve ALL DNS requests to local ip
* prerequisites for lnxrouter procps, dnsmasq, iproute2, iptables, hostapd, iw/iwconfig, haveged
* install nginx, use modified config to include all additional config files from spacewalk directory `conf.d`
* get nodogsplash, which doesn't have a package and has to be built on the device

    sudo apt-get install libmicrohttpd-dev
    git clone https://github.com/nodogsplash/nodogsplash.git
    cd nodogsplash
    make
    make install

* nodogsplash config should be changed to listen on wlan0 `/etc/nodogsplash/nodogsplash.conf`
* unofficial wiringpi, because the official one doesn't work with all raspis https://github.com/WiringPi/WiringPi/releases/tag/2.61-1 (copied to this repo just for ease of uses)

        sudo dpkg -i [filename of wiring pi]

* install jq to make it possible edit some of the jsons from bash
* install moreutils to get the sponge command
* install php8.1-fpm so that nginx can serve php. This is a bit tricker for ARMv6, here is the procedure that workd (just replace with php8.1-fpm where appropriate):

        sudo apt update
        sudo apt -y install lsb-release apt-transport-https ca-certificates
        sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
        echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list
        sudo apt update
        sudo apt -y install php7.4  

* php config at /etc/php/8.1/fpm/pool.d/www.conf should be modified so that listen.owner/group is our webserver (nginx) and user/group is our main user (e.g. simukas)

#### TODOS:

- [ ] Add correct configs and copy them over when setting up for
    - [ ] Nginx root config
    - [ ] php8.1-fpm
    - [ ] nodogsplash
- [ ] Create a shutdown script that shut things down gracefully
    - [ ] shutting down lnxrouter needs some pid grepping
    - [ ] don't know how to shut nodogsplash gracefully
