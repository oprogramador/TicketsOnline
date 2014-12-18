# script to set up the symfony app on any brand new debian derivative (after litte changes on any brand new linux)
# run this script with at least 1.8 GB of RAM
db=TicketsOnline
dbUser=TicketsOnlineUser
dbPass=gAnVnxJJx38F7WQYTA0d
path=cgi/examples/
project=TicketsOnline


sudo apt-get update &&
toinst=(realpath curl git apache2 php5 php5-cli mysql-client mysql-server php5-mysql) &&
for i in "${toinst[@]}"; do
    echo $i &&
    echo y | sudo apt-get install $i
done &&
cd $(dirname $(realpath $0)) &&
./createParams.sh 127.0.0.1 null $db $dbUser $dbPass &&
printf "\n\nmysql:" &&
mysql -u root -p -e "grant all on *.* to 'dbUser'@'localhost' identified by 'dbPass'"
cd .. &&
cd ~ &&
if [ ! -f composer.phar ]; then
    curl -sS https://getcomposer.org/installer | php
fi &&
cd - &&
php ~/composer.phar update --no-scripts &&
php app/console doctrine:database:create &&
php app/console doctrine:schema:update --force &&
php app/console doctrine:fixtures:load &&
php app/console cache:clear &&
curdir=`pwd` &&
cd - &&
sudo chmod -R 777 . &&
while [ `pwd` != '/' ]; do
    sudo chmod 777 . &&
    cd ..
done &&
cd $curdir &&
sudo mkdir -p /var/www/html/$path &&
if [ ! -L /var/www/html/$path$project ]; then
    sudo ln -s `realpath .` /var/www/html/$path$project
fi &&
sudo /etc/init.d/apache2 restart &&
gnome-open http://localhost/$path$project/web/app.php/ 
