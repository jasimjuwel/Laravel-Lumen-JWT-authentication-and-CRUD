<?php

namespace App\Http\Controllers;

use App\Product;
use App\Traits\FileHandlerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends ApiController
{
    use FileHandlerTrait;

    /**
     * Store a new product.
     *
     * @param Request $request
     * @return Response
     */
    public function index()
    {
        try {
            $prodctsList = Product::orderBy('id', 'asc')->get();

            $this->response = [
                'status' => true,
                'message' => 'Product List',
                'data' => $prodctsList
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
            $product = new Product;
            $product->title = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->image = $request->image;
            if ($request->hasFile('image')) {
                $fileName = $this->processImage($request, 'image', 'User/', 413, 531, [
                    [
                        'path' => 'thumbnails/',
                        'width' => 50,
                        'height' => 50,
                        'crop_resize' => 'resize'
                    ]
                ], 'resize');
                $product['image'] = $fileName;
            }
            $product->save();

            $this->response = [
                'status' => true,
                'message' => 'Product Created',
                'data' => $product
            ];

            return $this->responseSuccess($this->response);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    public function show(Product $product)
    {
        try {
            $data = $product->first();

            $this->response = [
                'status' => true,
                'message' => 'Product Details',
                'data' => $data
            ];

            return $this->responseSuccess($this->response);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    public function edit(Product $product)
    {
        try {
            $data = $product->first();

            $this->response = [
                'status' => true,
                'message' => 'Product Details',
                'data' => $data
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
            $product = Product::findOrFail($id);

            $product->title = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;

            if ($request->hasFile('image')) {
                if (file_exists(config('siteConfig.upload_dir') . $product->image)) {
                    unlink(config('siteConfig.upload_dir') . $product->image);
                }

                $fileName = $this->processImage($request, 'image', 'User/', 413, 531, [
                    [
                        'path' => 'thumbnails/',
                        'width' => 50,
                        'height' => 50,
                        'crop_resize' => 'resize'
                    ]
                ], 'resize');
                $product->image = $fileName;
            }

            $product->save();

            $this->response = [
                'status' => true,
                'message' => 'Product Updated',
                'data' => $product
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
            $product = Product::findOrFail($id);
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
}
