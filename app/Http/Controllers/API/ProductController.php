<?php

namespace App\Http\Controllers\API;

use App\Product;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::paginate();

        return $this->successResponse($product, 'Product retrieved sucessfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $data = $request->only('name', 'sku', 'price', 'description', 'quantity', 'status');

        $product = Product::create($data);

        if ($product->exists()) {
            return $this->successResponse($product, 'Product created sucessfully', 200)->header('Accept', 'application/json');
        }
        return $this->errorResponse('Fail to create product', 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return $this->successResponse($product, 'Products retrieved sucessfully', 200);
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Product Image ID ' . $id . ' dont exist', 404);
        } catch (\Exception $e) {
            echo $e->getMessage(), "\n";
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->only('name', 'sku', 'price', 'description', 'quantity', 'status'));

        if ($product->exists()) {
            return $this->successResponse($product, 'Product updated successfully', 200)->header('Accept', 'application/json');
        }
        return $this->errorResponse('Product image fail to update', 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            $FileSystem = new Filesystem();
            // Target directory.
            $directory = storage_path('app/public/img/') . $product->id;

            // Check if the directory exists.
            if ($FileSystem->exists($directory)) {

                // Get all files in this directory.
                $files = $FileSystem->files($directory);

                // Check if directory is empty.
                if (empty($files)) {
                    // Yes, delete the directory.
                    Storage::deleteDirectory($product->id);
                    $product->delete();
                } else {
                    $productImages = Product::with('productImages')->findOrFail($id);
                    $collection = collect($productImages);
                    $names = $collection->collapse()->pluck('name');

                    foreach ($names as $name) {
                        Storage::disk('images')->delete($name);
                    }
                    Storage::deleteDirectory($product->id);
                    $product->delete();
                }
            }

            return $this->successResponse($product, 'Product ID ' . $id . ' deleted successfully with the image associated with it.', 200);
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Product Image ID ' . $id . ' dont exist', 404);
        } catch (\Exception $e) {
            echo $e->getMessage(), "\n";
        }
    }
}
