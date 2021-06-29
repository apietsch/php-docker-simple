# start container
`docker-compose up`
or 
`docker-compose up -d`

# cleanup after shutdown with Control-C
`docker-compose rm -f`
`docker volume rm php-docker-simple_mysql-data`

# Cleanup everything from container
`docker-compose down -v --rmi all`

# to stop:
`docker-compose down`

# adminer on port 80 with root/example

You can do this in `http://localhost:8080` (adminer) with "root" as user, "example" as password.  "MySQL" selected for the system, and "db" as the server.

See `mysql-init-files/init_db.sql` where the required tables are created.

The db `mydatabase` is created by `MYSQL_DATABASE: mydatabase` in `docker-compose.yml`.