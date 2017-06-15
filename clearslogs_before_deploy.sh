#!/bin/bash

#if [ "$USER_NAME" != "root" ]; then
	#echo "hello" $USER_NAME
#fi


#чистим директории перед деплоем

find ./.sessions/ -name "sess_*" -type f  -delete
echo "почистили сессии..."

find ./logs/php/ -name "*201?-php.php" -type f  -delete
find ./logs/php/ -name "phperrors.log" -type f  -delete
find ./logs/sql/ -name "*sql.*" -type f  -delete
find ./logs/api/ -name "api_log*.*" -type f  -delete
find ./logs/api/requests/ -name "*.*" -type f  -delete
find ./logs/api/responses/ -name "*.*" -type f  -delete
find ./logs/cron/ -name "*.*" -type f  -delete
find ./logs/np/ -name "201?*.php" -type f  -delete
echo "почистили логи..."


find ./dumper/backup/ -name "horoshop*.sql.gz" -type f  -delete
echo "почистили бекапы БД..."

find ./.cache/store/ -type f  -delete
echo "почистили .cache"

echo "ВСЕ! но это не точно..."