# Sandox
Ce projet est un bac à sable permettant de tester des comportements ou fonctionnalités. 


## Installation
```
composer install
```

### VHOST
```
sudo ln -s /var/www/sites/sandbox/host/api-sandbox.conf /etc/nginx/sites-enabled/api-sandbox.conf
sudo ln -s /var/www/sites/sandbox/host/api-sandbox.conf /etc/nginx/sites-available/api-sandbox.conf

sudo service nginx restart

sudo chown -R www-data:www-data /var/www/sites/sandbox/sites/api/storage
sudo chown -R www-data:www-data /var/www/sites/sandbox/sites/api/vendor
Sudo usermod -a -G www-data roloza
```