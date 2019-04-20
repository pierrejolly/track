Track
=====

## Prérequis

- php >= 5.5
- [composer](https://getcomposer.org/doc/00-intro.md#globally)

## Installation

    git clone git@github.com:pierrejolly/track.git
    composer install

## Configuration des permissions

    rm -rf var/cache/* var/logs/*

Système supportant chmod +a (OS X)

    HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
    sudo chmod +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" var/cache var/logs
    sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" var/cache var/logs

[Plus d'information](http://symfony.com/doc/current/book/installation.html#configuration-and-setup)

## Lancer les commandes suivante

    php bin/console doctrine:database:create
    php bin/console doctrine:schema:create

## Optionnel

### Fixtures

    php bin/console hautelook_alice:doctrine:fixtures:load