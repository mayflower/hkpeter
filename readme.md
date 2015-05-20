hkpeter
========

prerequisites (dev)
-------------------

- install virtualbox and vagrant (e.g. via [brew-cask](https://github.com/caskroom/homebrew-cask#lets-try-it))  
  `brew cask install virtualbox vagrant`

- install optional vagrant plugins  
  `vagrant plugin install vagrant-vbguest vagrant-hostmanager vagrant-cachier`

setup (dev)
-----------

- clone this repository and cd into it  
  `git clone git@github.com:elseym/hkpeter.git && cd hkpeter`

- bootstrap the vagrant config (don't overwrite!)  
  `./vagrant/bootstrap.sh`

- fire up the vm  
  `vagrant up`

- install dependencies  
  `vagrant ssh -c "cd /var/www/hkpeter && composer install"`

reload (dev)
------------

- to recover from a failing environment, just re-create your box and run setup again  
  - `vagrant destroy`
  - `vagrant up`
  - `vagrant ssh -c "cd /var/www/hkpeter && composer install"`

run tests (dev)
---------------

- `vagrant ssh -c "cd /var/www/hkpeter && bin/phpunit -c app/"`