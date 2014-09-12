# hkpeter
### a hkp keyserver

## installation
- `git clone` this repo
- `./vagrant/bootstrap.sh` but don't overwrite anything
- `vagrant up` the box (see [Mayflower/wasted](https://github.com/mayflower/wasted) for more info.)
- `vagrant ssh` into the box
- `cd /var/www/hkpeter && composer install` the dependencies
