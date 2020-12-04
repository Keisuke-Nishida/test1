#Takii Wbs Project

### local setting
1.mysql create database
> create database takii-wbs

2.console command
>$ cd DOCUMENT_ROOT  
>$ composer install  
>$ php artisan migrate  
>$ php artisan db:seed

・database refresh
>$ php artisan migrate:refresh --seed

・error does not exist
>$ composer dump-autoload


### web
web account
- http://localhost

 >login_id: user001  
 >password: test1234

### admin
admin account
- http://localhost/admin

 >login_id: admin001  
 >password: test1234
