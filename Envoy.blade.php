@servers(['local' => '127.0.0.1'])

@setup
$appPath = '/var/www/inventory';
$branch = 'kentwood';
@endsetup

@story('deploy')
pull
composer
npm
clear_cache
optimize
permissions
success
@endstory

@task('pull', ['on' => 'local'])
echo "🔄 Pulling latest code from {{ $branch }}..."
cd {{ $appPath }}
sudo git pull origin {{ $branch }}
@endtask

@task('composer', ['on' => 'local'])
echo "📦 Installing Composer dependencies..."
cd {{ $appPath }}
sudo -u www-data composer install --no-dev --optimize-autoloader
@endtask

@task('npm', ['on' => 'local'])
echo "🎨 Building frontend assets..."
cd {{ $appPath }}
sudo npm install
sudo npm run build
@endtask

@task('clear_cache', ['on' => 'local'])
echo "🧹 Clearing caches..."
cd {{ $appPath }}
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear
@endtask

@task('optimize', ['on' => 'local'])
echo "⚡ Optimizing application..."
cd {{ $appPath }}
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
@endtask

@task('permissions', ['on' => 'local'])
echo "🔒 Setting permissions..."
sudo chown -R www-data:www-data {{ $appPath }}
sudo chmod -R 775 {{ $appPath }}/storage {{ $appPath }}/bootstrap/cache
@endtask

@finished
echo "✅ Deployment completed successfully!"
@endfinished

@task('success', ['on' => 'local'])
echo "🚀 Application deployed to {{ $appPath }}"
@endtask
