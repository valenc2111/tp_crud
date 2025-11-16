#para ejecutarlo desde la terminal usar: ./start.sh
#si no tiene permisos de ejecucion, usar: chmod +x start.sh y luego ./start.sh
sudo service apache2 start
sudo service mariadb start
php8.3 -S localhost:8000