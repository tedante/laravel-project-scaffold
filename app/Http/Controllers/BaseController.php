<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\UnprocessEntityException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class BaseController extends Controller
{
    protected $model;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requestQuery = request()->query();

        $limit = $requestQuery['perPage'] ?? 25;

        $data = $this->model::query();

        if (isset($requestQuery['filters'])) {
            $produk = new $this->model();

            $column = $produk->getConnection()
                            ->getSchemaBuilder()
                            ->getColumnListing(
                                $produk->getTable()
                            );

            foreach($requestQuery['filters'] as $key => $value) {
                $filters = explode(",", $value);
                
                if (in_array($key, $column)) {
                    foreach ($filters as $item) {
                        $data = $data->where($key, 'like', "%".$item."%");
                    }
                }
            }
        }

        if(isset($requestQuery['sortBy'])) {
            $data = $data->orderBy($requestQuery['sortBy'], $requestQuery['dir']);
        }
        
        $data = $data->paginate($limit);
    
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
