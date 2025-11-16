#para ejecutarlo desde la terminal usar: ./script.sh
#si no tiene permisos de ejecucion, usar: chmod +x script.sh y luego ./script.sh
sudo service apache2 start
sudo service mariadb start
php8.3 -S localhost:8000