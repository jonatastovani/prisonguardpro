#!/bin/bash
set -e

# Ajustar as permissões do diretório storage
chmod -R 775 /var/www/html/storage

# Executar o comando passado como argumentos (como apache2-foreground)
exec "$@"
