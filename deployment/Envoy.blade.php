//  Author: Raul Perusquia <raul@inikoo.com>
//  Created: Sun, 05 Sep 2021 20:27:54 Malaysia Time, Kuala Lumpur, Malaysia
//  Copyright (c) 2021, Inikoo
//  Version 4.0


@servers(['production' => $user.'@'.$host,'localhost' => '127.0.0.1'])

@setup
// Sanity checks
if (empty($host)) {
    exit('ERROR: $host var empty or not defined');
}
if (empty($user)) {
    exit('ERROR: $user var empty or not defined');
}
if (empty($path)) {
    exit('ERROR: $path var empty or not defined');
}
if (empty($build)) {
    exit('ERROR: $build var empty or not defined');
}
if (empty($commit)) {
    exit('ERROR: $commit var empty or not defined');
}

if (file_exists($path) || is_writable($path)) {
    exit("ERROR: cannot access $path");
}

// Ensure given $path is a potential web directory (/home/* or /var/www/*)
if (!(preg_match("/(\/home\/|\/var\/www\/)/i", $path) === 1)) {
    exit('ERROR: $path provided doesn\'t look like a web directory path?');
}

$date = ( new DateTime )->format('Y-m-d_H:i:s');

$current_release_dir = $path . '/current';
$releases_dir = $path . '/releases';
$stagging_dir = $path . '/stagging';
$repo_dir = $path . '/repo';
$new_release_dir = $releases_dir . '/' . $date;


// Command or path to invoke PHP
$php = empty($php) ? 'php' : $php;

@endsetup



@story('deploy')
create_folders
pull
stagging
setup_symlinks
composer_install
npm_install
build
manifest_file
move_to_release_dir
verify_install
activate_release
optimise
migrate
additional_tasks
cleanup
@endstory



@task('create_folders', ['on' => 'production'])
mkdir -p {{ $new_release_dir }}
mkdir -p {{ $new_release_dir }}/public/
@endtask

@task('pull', ['on' => 'production'])
echo "* Pulling repo *"
cd {{$repo_dir}}
git pull origin {{ $branch }}
@endtask


@task('stagging', ['on' => 'production'])
echo "* Stagging code from {{ $repo_dir }} to {{ $stagging_dir }} *"
rsync   -rlptgoDPzSlh  --no-p --chmod=g=rwX  --delete  --stats --exclude-from={{ $repo_dir }}/deployment/deployment-exclude-list.txt {{ $repo_dir }}/ {{ $stagging_dir }}
@endtask

@task('composer_install', ['on' => 'production'])
echo "* Composer install *"
cd {{$stagging_dir}}

/usr/bin/php8.0  /usr/local/bin/composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader --prefer-dist


@endtask

@task('npm_install', ['on' => 'production'])
echo "* NPM install *"
cd {{$stagging_dir}}
npm install
@endtask

@task('build', ['on' => 'production'])
echo "* build VUE *"
cd {{$stagging_dir}}
ln -sf {{ $path }}/private/ {{ $stagging_dir }}/resources/js/
npm run prod
@endtask

@task('manifest_file', ['on' => 'production'])
echo "* Writing deploy manifest file *"

echo -e "{\"build\":\""{{ $build }}"\", \"commit\":\""{{ $commit }}"\", \"branch\":\""{{ $branch }}"\"}" > {{ $new_release_dir }}/deploy-manifest.json
@endtask




@task('setup_symlinks', ['on' => 'production'])


echo "* Linking .env file to new release dir ({{ $path }}/.env -> {{ $new_release_dir }}/.env) *"
ln -nsf {{ $path }}/.env {{ $new_release_dir }}/.env



echo "* Linking storage directory to new release dir ({{ $path }}/storage -> {{ $new_release_dir }}/storage) *"
ln -nsf {{ $path }}/storage {{ $new_release_dir }}/storage
echo "* Linking storage directory to new release dir ({{ $path }}/storage/app/public -> {{ $new_release_dir }}/public/storage) *"
ln -nsf {{ $path }}/storage/app/public {{ $new_release_dir }}/public/storage
@endtask


@task('move_to_release_dir', ['on' => 'production'])
echo "* Moving code from src  to {{ $new_release_dir }} *"
rsync -auz --exclude 'node_modules' {{ $stagging_dir }}/ {{ $new_release_dir }}
@endtask

@task('verify_install', ['on' => 'production'])
echo "* Verifying install ({{ $new_release_dir }}) *"
cd {{ $new_release_dir }}
{{ $php }} artisan --version
@endtask

@task('activate_release', ['on' => 'production'])
echo "* Activating new release ({{ $new_release_dir }} -> {{ $current_release_dir }}) *"
ln -nsf {{ $new_release_dir }} {{ $current_release_dir }}
@endtask

@task('migrate', ['on' => 'production'])
echo '* Running migrations *'
cd {{ $new_release_dir }}
{{ $php }} artisan migrate --force  --path=database/migrations/landlord --database=landlord
{{ $php }} artisan tenants:artisan "migrate --force   --database=tenant" --tenant=1
@endtask

@task('optimise', ['on' => 'production'])
echo '* Clearing cache and optimising *'
cd {{ $new_release_dir }}



{{ $php }} artisan optimize:clear --quiet

/usr/bin/php8.0  /usr/local/bin/composer dump-autoload -o


echo "Queue restarted"
#{{ $php }} artisan queue:restart --quiet

echo "Cache"
{{ $php }} artisan config:cache
{{ $php }} artisan view:cache

# Only use when no closure used in routes
#{{ $php }} artisan optimize
{{ $php }} artisan route:cache

echo "Deployment ({{ $date }}) finished"
@endtask

@task('additional_tasks', ['on' => 'production'])
cd {{ $new_release_dir }}

echo "* Additional Tasks *"

@endtask

@task('cleanup', ['on' => 'production'])
echo "* Executing cleanup command in {{ $releases_dir }} *"

cd {{$releases_dir}}
ls -t | tail -n +2 | xargs rm -rf

@endtask

@task('deployment_rollback')
#cd {{ $path }}
#ln -nsf {{ $path }}/$(find . -maxdepth 1 -name "20*" | sort | tail -n 2 | head -n1) {{ $path }}/current
echo "Rolled back to $(find . -maxdepth 1 -name "20*" | sort | tail -n 2 | head -n1)"
@endtask
