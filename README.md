
## Laravel Project Scaffold
This is a web application based on laravel framework with some code for a simple project.

On this app is include:
- [fruitcake/laravel-cors](https://github.com/fruitcake/laravel-cors/)
- [guzzlehttp/guzzle](https://github.com/guzzlehttp/guzzle)
- [intervention/image](https://github.com/intervention/image)
- [laravel/framework](https://github.com/laravel/framework)
- [laravel/passport](https://github.com/[laravel/passport)
- [laravel/tinker](https://github.com/laravel/tinker)
- [maatwebsite/excel](https://github.com/maatwebsite/excel)

### Installation
1. Clone this repo.
2. On terminal run "composer install"
3. On terminal run "php artisan migrate --seed"
4. On terminal run "php artisan passport::install"
5. After step 4 you can check the login and register.
6. If you want create new module. Create a migration and model then copy a controller from ExampleController.php and rename controller to name you want.
7. Add route resource to new controller.
8. Now you can access route CRUD for that model.