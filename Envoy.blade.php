@servers(['local' => '127.0.0.1'])

@task('deploy', ['on' => 'local'])
cd /var/www/inventory
sudo git pull origin kentwood
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo npm install
sudo npm run build
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo chown -R www-data:www-data /var/www/inventory
sudo chmod -R 775 /var/www/inventory/storage /var/www/inventory/bootstrap/cache
@endtask
