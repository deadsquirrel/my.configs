#!/bin/bash

USER_NAME=`whoami`

if [ "$USER_NAME" != "root" ]; then
	USER_NAME="$USER_NAME"
	echo "hello, ${USER_NAME}!"
	echo "       "
fi


#проверяем не пустой ли параметр на входе
if [ "$1" == "" ]; then
    echo "You must define project's name"
    exit 1
fi

PROJECT_NAME="$1"
echo "New project is ${PROJECT_NAME}"


PROJECT_DIR="/home/yanki/prj/${PROJECT_NAME}"
echo "New project DIR is ${PROJECT_DIR}"
#проверяем наличие домашней директории
if [ -d "${PROJECT_DIR}" ]; then
	echo "Directory ${PROJECT_DIR} is already exists!"
fi


#чистим директории перед деплоем

find ${PROJECT_DIR}/.sessions/ -name "sess_*" -type f  -delete
echo "почистили сессии..."

find ${PROJECT_DIR}/logs/php/ -name "*201?-php.php" -type f  -delete
find ${PROJECT_DIR}/logs/php/ -name "phperrors.log" -type f  -delete
find ${PROJECT_DIR}/logs/sql/ -name "*sql.*" -type f  -delete
find ${PROJECT_DIR}/logs/api/ -name "api_log*.*" -type f  -delete
find ${PROJECT_DIR}/logs/api/requests/ -name "*.*" -type f  -delete
find ${PROJECT_DIR}/logs/api/responses/ -name "*.*" -type f  -delete
find ${PROJECT_DIR}/logs/cron/ -name "*.*" -type f  -delete
find ${PROJECT_DIR}/logs/np/ -name "201?*.php" -type f  -delete
echo "почистили логи..."


find ${PROJECT_DIR}/dumper/backup/ -name "horoshop*.sql.gz" -type f  -delete
echo "почистили бекапы БД..."

find ${PROJECT_DIR}/.cache/store/ -type f  -delete
echo "почистили .cache"

echo "ВСЕ! но это не точно..."