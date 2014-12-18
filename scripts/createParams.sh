cd $(dirname $(realpath $0))
cd ..
cat <<EOF > app/config/parameters.yml
parameters:
    database_driver: pdo_mysql
    database_host: $1
    database_port: $2
    database_name: $3
    database_user: $4
    database_password: $5
    mailer_transport: smtp
    locale: en
    secret: ThisTokenIsNotSoSecretChangeIt
EOF
