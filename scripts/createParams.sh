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
    info_domain: $6
    info_path: $7
    info_project: $8
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: null
    mailer_password: null
    locale: en
    secret: ThisTokenIsNotSoSecretChangeIt
EOF
