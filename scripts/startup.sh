# script to set up the symfony app on any brand new debian derivative (after litte changes on any brand new linux)
# run this script with at least 1.8 GB of RAM
db=TicketsOnline
dbUser=TiOnUser
dbPass=gAnVnxJJx38F7WQYTA0d
path=cgi/examples/
project=TicketsOnline
domain=localhost

sudo apt-get update &&
toinst=(realpath curl git apache2 php5 php5-cli mysql-client mysql-server php5-mysql) &&
for i in "${toinst[@]}"; do
    echo $i &&
    echo y | sudo apt-get install $i
done &&
cd $(dirname $(realpath $0)) &&
./createParams.sh 127.0.0.1 null $db $dbUser $dbPass $domain $path $project &&
printf "\n\nmysql:" &&
mysql -u root -p -e "grant all on $db.* to '$dbUser'@'localhost' identified by '$dbPass'; flush privileges; drop database if exists $db" &&
cd .. &&
cd ~ &&
if [ ! -f composer.phar ]; then
    curl -sS https://getcomposer.org/installer | php
fi &&
cd - &&
php ~/composer.phar update --no-scripts &&
sudo php app/console doctrine:database:create &&
sudo php app/console doctrine:schema:update --force &&
sudo php app/console doctrine:fixtures:load &&
sudo php app/console cache:clear &&
sudo php app/console cache:clear --env=prod &&
pushd . &&
sudo chmod -R 777 . &&
while [ `pwd` != '/' ]; do
    sudo chmod 777 . &&
    cd ..
done &&
popd &&
sudo mkdir -p /var/www/html/$path &&
if [ ! -L /var/www/html/$path$project ]; then
    sudo ln -s `realpath .` /var/www/html/$path$project
fi &&
sudo /etc/init.d/apache2 restart &&
gnome-open http://$domain/$path$project/web/app.php/en/
