# Mail & SMS Service
```shell
RELEASE = 'php artisan migrate:fresh --seed'
```
## RabbitMQ consume command
```shell
php artisan queue:work --queue=sms.q,mail.q
```
