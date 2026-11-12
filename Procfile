web: php artisan config:clear && (php artisan migrate --force || true) && (php artisan db:seed --force || true) && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
