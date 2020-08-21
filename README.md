
## Laravel Project Scaffold
This is an application based on laravel framework with some code for a general api project.

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
6. If you want create new module. Create a migration and model then copy and rewrite a controller and model code down below and save controller to name you want.
7. Add route resource to new controller.
8. Now you can access route CRUD for that model.

### Example Controller
```
<?php

namespace App\Http\Controllers;

class ExampleController extends BaseController
{
  protected $model = 'App\Models\Example';

  protected $validation = [
    'name' => 'string|required',
    'unit_id' => 'integer|exists:units,id|required',
    'category_id' => 'integer|exists:categories,id|required',
    'material_type_id' => 'integer|exists:material_types,id|required',
    'material_group' => 'string',
    'code' => 'string',
    'excerpt' => 'string',
    'description' => 'string',
    'document_link' => 'string',
    'images.*.image_link' => 'string|required_with:images',
    'images.*.position' => 'integer|min:1|required_with:images',
    'plant' => 'string',
    'prices' => 'required|numeric',
    'discount_percentage' => 'required|numeric',
    'is_active' => 'required|boolean'
  ];
}
```

### Example Mode
```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
  
  protected $fillable = [
    'unit_id',
    'category_id',
    'material_type_id',
    'material_group',
    'name',
    'code',
    'excerpt',
    'description',
    'document_link',
    'plant',
    'prices',
    'discount_percentage',
    'is_active'
  ];

  public function getAllRelation() {
    // return name of all relation function 
    return [
      'unit',
      'category',
      'material_type'
    ];
  }

  public function unit() {
    return $this->belongsTo(Unit::class);
  }

  public function category() {
    return $this->belongsTo(Category::class);
  }

  public function material_type(){
    return $this->belongsTo(MaterialType::class);
  }

}
```