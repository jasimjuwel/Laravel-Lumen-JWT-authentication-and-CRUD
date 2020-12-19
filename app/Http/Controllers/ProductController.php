<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductsResource;
use App\Product;
use App\Traits\FileHandlerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends ApiController
{
    use FileHandlerTrait;

    protected $produt;

    /**
     * Create a new controller instance.
     */
    public function __construct(Request $request)
    {
        $this->product = new Product;
    }

    /**
     * Store a new product.
     *
     * @param Request $request
     * @return Response
     */
    public function index()
    {
        try {
            $prodctsList = $this->product->orderBy('id', 'asc')->get();

            $this->response = [
                'status' => true,
                'message' => 'Product List',
                'data' => new ProductsResource($prodctsList),
            ];

            return $this->responseSuccess($this->response);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    public function store(Request $request)
    {
        //validate incoming request
        $rules = [
            'title' => 'required|string|between:2,100|unique:products',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->responseValidationError($validator->messages());
        }

        try {
            $product = $this->product;
            $product->title = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->image = $request->image;
            if ($request->hasFile('image')) {
                $fileName = $this->processImage($request, 'image', 'User/', 413, 531, 'resize');
                $product['image'] = $fileName;
            }
            $product->save();

            $this->response = [
                'status' => true,
                'message' => 'Product Created',
                'data' => new ProductResource($product),
            ];

            return $this->responseSuccess($this->response);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    public function show($id)
    {
        try {
            $data = $this->product->findOrFail($id);

            $this->response = [
                'status' => true,
                'message' => 'Product Details',
                'data' => new ProductResource($data),
            ];

            return $this->responseSuccess($this->response);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->product->findOrFail($id);

            $this->response = [
                'status' => true,
                'message' => 'Product Details',
                'data' => new ProductResource($data),
            ];

            return $this->responseSuccess($this->response);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    public function update(Request $request, $id)
    {
        //validate incoming request
        $rules = [
            'title' => 'required|string|between:2,100|unique:products,title,' . $id,
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->responseValidationError($validator->messages());
        }

        try {
            $product = $this->product->findOrFail($id);

            $product->title = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;

            if ($request->hasFile('image')) {
                $this->deleteImage($product->image);

                $fileName = $this->processImage($request, 'image', 'User/', 413, 531, 'resize');
                $product->image = $fileName;
            }

            $product->save();

            $this->response = [
                'status' => true,
                'message' => 'Product Updated',
                'data' => new ProductResource($product),
            ];

            return $this->responseSuccess($this->response);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    public function delete($id)
    {
        try {
            $product = $this->product->findOrFail($id);
            $this->deleteImage($product->image);
            $product->delete();

            $this->response = [
                'status' => true,
                'message' => 'Product Deleted',
                'data' => null
            ];

            return $this->responseSuccess($this->response);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    private function deleteImage($fileName)
    {
        if (file_exists(config('siteConfig.upload_dir') . $fileName)) {
            unlink(config('siteConfig.upload_dir') . $fileName);
        }
    }
}
