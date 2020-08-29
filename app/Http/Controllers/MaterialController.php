<?php

namespace App\Http\Controllers;

class MaterialController extends BaseController
{
    protected $model = 'App\Models\Material';
  
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