{{--
//  Author: Raul Perusquia <raul@inikoo.com>
//  Created: Sun, 05 Sep 2021 20:27:54 Malaysia Time, Kuala Lumpur, Malaysia
//  Copyright (c) 2021, Inikoo
//  Version 4.0
--}}

@include('../vendor/autoload.php')

@servers(['production' => $user.'@'.$host,'localhost' => '127.0.0.1'])

@setup
$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();

$api_url=$_ENV['PRODUCTION_API_URL'];
$api_key=$_ENV['PRODUCTION_API_KEY'];


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
$staging_dir = $path . '/staging';
$repo_dir = $path . '/repo';
$new_release_dir = $releases_dir . '/' . $date;

$env_file='/home/vagrant/aiku-io/deployment/.env';

// Command or path to invoke PHP
$php = empty($php) ? 'php8.0' : $php;
$branch = empty($branch) ? 'master' : $branch;

$deployment_key=null;

$skip_build=false;

@endsetup

@story('first_time_DANGER')
confirm_reset_DANGER
confirm_reset_DANGER_2
create_folders
pull
staging
setup_symlinks
composer_install_first_time
move_to_release_dir
verify_install
setup_first_time_DANGER_FINAL_WARNING

@endstory


@story('deploy')
start_deployment
create_folders
pull
staging
setup_symlinks
composer_install
npm_install
build
move_to_release_dir
verify_install
activate_release
optimise
migrate
additional_tasks
cleanup
@endstory

@task('composer_install_first_time', ['on' => 'production'])
echo "* Setting up first time *"
cd {{$staging_dir}}
/usr/bin/php8.0  /usr/local/bin/composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader --prefer-dist


@endtask

@task('confirm_reset_DANGER', ['on' => 'production','confirm' => true])
echo "* This will DELETE aiku database are you sure!!!!*"
@endtask
@task('confirm_reset_DANGER_2', ['on' => 'production','confirm' => true])
echo "* DANGER AHEAD*"
@endtask



@task('setup_first_time_DANGER_FINAL_WARNING', ['on' => 'production','confirm' => true])
echo "* Setting up first time *"
cd {{$new_release_dir}}
/usr/bin/php8.0  /usr/local/bin/composer dump-autoload -o

/usr/bin/php8.0 artisan migrate:fresh --force --path=database/migrations/landlord --database=landlord
/usr/bin/php8.0 artisan db:seed  --force  --database=landlord

echo /usr/bin/php8.0 artisan admin:new --randomPassword '{{$adminName}}' {{$adminEmail}} {{$adminSlug}}

/usr/bin/php8.0 artisan admin:new --randomPassword '{{$adminName}}' {{$adminEmail}} {{$adminSlug}}
/usr/bin/php8.0 artisan admin:token {{$adminSlug}}

@endtask



@task('start_deployment', ['on' => 'localhost'])
DEPLOY=$(curl --silent --location --request POST '{{$api_url}}/deployments/create' --header 'Authorization: Bearer {{$api_key}}')
echo $DEPLOY | jq -r '.version'
@endtask




@task('create_folders', ['on' => 'production'])
mkdir -p {{ $new_release_dir }}
mkdir -p {{ $new_release_dir }}/public/
@endtask

@task('pull', ['on' => 'production'])
echo "* Pulling repo *"
cd {{$repo_dir}}
git pull origin {{ $branch }}
@endtask


@task('staging', ['on' => 'production'])
echo "* staging code from {{ $repo_dir }} to {{ $staging_dir }} *"
rsync   -rlptgoDPzSlh  --no-p --chmod=g=rwX  --delete  --stats --exclude-from={{ $repo_dir }}/deployment/deployment-exclude-list.txt {{ $repo_dir }}/ {{ $staging_dir }}
@endtask




@task('composer_install', ['on' => 'production'])
echo "* Composer install *"

DEPLOY=$(curl --silent --location --request GET '{{$api_url}}/deployments/{{$deployment_id}}' --header 'Authorization: Bearer {{$api_key}}')
echo $DEPLOY | jq -r '.version'

cd {{$staging_dir}}
/usr/bin/php8.0  /usr/local/bin/composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader --prefer-dist


@endtask

@task('npm_install', ['on' => 'production'])
echo "* NPM install *"
cd {{$staging_dir}}
npm install
@endtask

@task('build', ['on' => 'production'])
echo "* build VUE *"
cd {{$staging_dir}}
ln -sf {{ $path }}/private/ {{ $staging_dir }}/resources/js/
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
rsync -auz --exclude 'node_modules' {{ $staging_dir }}/ {{ $new_release_dir }}
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

@error
if ($task === 'deploy') {
// ...
}
@enderror

@after
if ($task === 'start_deployment') {
$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => $api_url.'/deployments/latest',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'GET',
CURLOPT_HTTPHEADER => array(
'Accept-Encoding: application/json',
'Accept: application/json',
'Authorization: Bearer '.$api_key
),
));

$response = curl_exec($curl);

curl_close($curl);


exit(1);

$deployment= json_decode($response,true);
$deployment_key=$deployment['id'];


}
@endafter
