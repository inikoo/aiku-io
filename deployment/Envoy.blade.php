{{--
//  Author: Raul Perusquia <raul@inikoo.com>
//  Created: Sun, 05 Sep 2021 20:27:54 Malaysia Time, Kuala Lumpur, Malaysia
//  Copyright (c) 2021, Inikoo
//  Version 4.0
--}}

@include('../vendor/autoload.php')


@setup
$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();

$api_url=$_ENV['PRODUCTION_API_URL'];
$api_key=$_ENV['PRODUCTION_API_KEY'];

$host=$_ENV['PRODUCTION_API_KEY'];

// Sanity checks

if (empty($_ENV['DEPLOYMENT_HOST'])) {
exit('ERROR: DEPLOYMENT_HOST var empty or not defined');
}
$host=$_ENV['DEPLOYMENT_HOST'];

if (empty($_ENV['DEPLOYMENT_USER'])) {
exit('ERROR: DEPLOYMENT_USER var empty or not defined');
}
$user=$_ENV['DEPLOYMENT_USER'];

if (empty($_ENV['DEPLOYMENT_PATH'])) {
exit('ERROR: DEPLOYMENT_PATH var empty or not defined');
}
$path=$_ENV['DEPLOYMENT_PATH'];






$date = ( new DateTime )->format('Y-m-d_H:i:s');

$current_release_dir = $path . '/current';
$releases_dir = $path . '/releases';
$staging_dir = $path . '/staging';
$repo_dir = $path . '/repo';
$new_release_dir = $releases_dir . '/' . $date;


// Command or path to invoke PHP
$php = empty($php) ? 'php8.0' : $php;
$branch = empty($branch) ? 'master' : $branch;

$deployment_key=null;

$skip_build=false;

@endsetup

@servers(['production' => $user.'@'.$host,'localhost' => '127.0.0.1'])


@story('first_time_DANGER')
confirm_reset_DANGER
confirm_reset_DANGER_2
setup_first_time_DANGER_FINAL_WARNING

@endstory



@task('confirm_reset_DANGER', ['on' => 'production','confirm' => true])
echo "* This will DELETE aiku database are you sure!!!!*"
@endtask
@task('confirm_reset_DANGER_2', ['on' => 'production','confirm' => true])
echo "* DANGER AHEAD*"
@endtask
@task('setup_first_time_DANGER_FINAL_WARNING', ['on' => 'production','confirm' => true])
echo "* Setting up first time *"

mkdir -p {{ $new_release_dir }}
mkdir -p {{ $new_release_dir }}/public/

echo "* Pulling repo *"

cd {{$repo_dir}}
git pull origin {{ $branch }}


rsync   -rlptgoDPzSlh  --no-p --chmod=g=rwX  --delete  --stats --exclude-from={{ $repo_dir }}/deployment/deployment-exclude-list.txt {{ $repo_dir }}/ {{ $staging_dir }}

ln -nsf {{ $path }}/.env {{ $new_release_dir }}/.env
ln -nsf {{ $path }}/storage {{ $new_release_dir }}/storage
ln -nsf {{ $path }}/storage/app/public {{ $new_release_dir }}/public/storage

cd {{$staging_dir}}
{{$php}}  /usr/local/bin/composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader --prefer-dist

rsync -auz --exclude 'node_modules' {{ $staging_dir }}/ {{ $new_release_dir }}

cd {{ $new_release_dir }}
{{ $php }} artisan --version

ln -nsf {{ $new_release_dir }} {{ $current_release_dir }}


cd {{$new_release_dir}}
{{$php}}  /usr/local/bin/composer dump-autoload -o
{{$php}} artisan migrate:fresh --force --path=database/migrations/landlord --database=landlord
{{$php}} artisan db:seed  --force  --database=landlord
{{$php}} artisan tenant:new  demo.aiku.io "Demo" --type=b2b
{{$php}} artisan admin:new --randomPassword '{{$adminName}}' {{$adminEmail}} {{$adminSlug}}
{{$php}} artisan admin:token {{$adminSlug}} admin root
@endtask


@task('deploy', ['on' => 'production'])
DEPLOY=$(curl --silent --location --request POST '{{$api_url}}/deployments/create' --header 'Authorization: Bearer {{$api_key}}')
echo $DEPLOY | jq -r '.version'
echo $DEPLOY > {{$path}}/deploy-manifest.json

mkdir -p {{ $new_release_dir }}
mkdir -p {{ $new_release_dir }}/public/

echo "* Pulling repo *"
cd {{$repo_dir}}
git pull origin {{ $branch }}

echo "* staging code from {{ $repo_dir }} to {{ $staging_dir }} *"
rsync   -rlptgoDPzSlh  --no-p --chmod=g=rwX  --delete  --stats --exclude-from={{ $repo_dir }}/deployment/deployment-exclude-list.txt {{ $repo_dir }}/ {{ $staging_dir }}
mv {{$path}}/deploy-manifest.json {{ $staging_dir }}/

ln -nsf {{ $path }}/.env {{ $new_release_dir }}/.env
ln -nsf {{ $path }}/storage {{ $new_release_dir }}/storage
ln -nsf {{ $path }}/storage/app/public {{ $new_release_dir }}/public/storage

DEPLOY=$(cat {{ $staging_dir }}/deploy-manifest.json | jq -r '.skip_composer_install' )
if [ $DEPLOY != true ]
then
    echo "* Composer install *"
    cd {{$staging_dir}}
    {{$php}}  /usr/local/bin/composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader --prefer-dist
fi

DEPLOY=$(cat {{ $staging_dir }}/deploy-manifest.json | jq -r '.skip_npm_install' )
if [ $DEPLOY != true ]
then
    echo "* NPM install *"
    cd {{$staging_dir}}
    npm install
fi

DEPLOY=$(cat {{ $staging_dir }}/deploy-manifest.json | jq -r '.skip_build' )
if [ $DEPLOY != true ]
then
    echo "* build VUE *"
    cd {{$staging_dir}}
    ln -sf {{ $path }}/private/ {{ $staging_dir }}/resources/js/
    npm run prod
fi

echo "* Sync code from {{ $staging_dir }}  to {{ $new_release_dir }} *"
rsync -auz --exclude 'node_modules' {{ $staging_dir }}/ {{ $new_release_dir }}

cd {{ $new_release_dir }}
{{ $php }} artisan --version

echo "* Activating new release ({{ $new_release_dir }} -> {{ $current_release_dir }}) *"
ln -nsf {{ $new_release_dir }} {{ $current_release_dir }}

echo '* Running migrations *'
cd {{ $new_release_dir }}
{{ $php }} artisan migrate --force  --path=database/migrations/landlord --database=landlord
{{ $php }} artisan tenants:artisan "migrate --force   --database=tenant"

echo '* Clearing cache and optimising *'
cd {{ $new_release_dir }}
{{ $php }} artisan optimize:clear --quiet
{{$php}}  /usr/local/bin/composer dump-autoload -o
echo "Queue restarted"
#{{ $php }} artisan queue:restart --quiet

echo "Cache"
{{ $php }} artisan config:cache
{{ $php }} artisan view:cache

# Only use when no closure used in routes
#{{ $php }} artisan optimize
{{ $php }} artisan route:cache

echo "* Executing cleanup command in {{ $releases_dir }} *"
cd {{$releases_dir}}
ls -t | tail -n +2 | xargs rm -rf

DEPLOY=$(curl --silent --location --request POST '{{$api_url}}/deployments/latest/edit?state=deployed' --header 'Authorization: Bearer {{$api_key}}')
echo $DEPLOY

echo "Deployment ({{ $date }}) finished"
@endtask


@error
if ($task === 'deploy') {
// ...
}
@enderror
