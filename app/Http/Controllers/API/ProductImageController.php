<?php

namespace App\Http\Controllers\API;

use App\Product;
use App\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Traits\Upload;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateProductImageRequest;
use App\Http\Requests\UpdateProductImageRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductImageController extends Controller
{
    use ApiResponser, Upload;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productImages = ProductImage::paginate();
        return $this->successResponse($productImages, 'Product image retrieved sucessfully', 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductImageRequest $request)
    {
        $data = $request->only('product_id', 'name', 'is_main');

        $productImages = new ProductImage;
        try {
            $productID = Product::where('id', $request->product_id)->first();

            if (isset($productID->id)) {

                $imageDataDecoded = base64_decode($request->name, true);
                $imageDataExtension = getimagesizefromstring($imageDataDecoded);
                $ext = image_type_to_extension($imageDataExtension[2]);
                $imageName = Str::uuid() . $ext;
                $this->uploadOne($imageDataDecoded, $productID->id, $imageName);
                $exists = Storage::disk('images')->exists("/{$productID->id}/" . $imageName);
                if (!$exists) {
                    return $this->errorResponse('Image fail to upload', 400);
                }
            }
            $data['product_id'] = $request->product_id;
            $data['name'] = $imageName;
            $productImages = ProductImage::create($data);
            return $this->successResponse($productImages, 'Product image ID ' . $request->product_id . ' uploaded successfully', 200)->withHeaders(['Accept' => 'application/json', 'Content-Type' => 'application/json',]);
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Product Image with that ID dont exist', 404);
        } catch (\Exception $exception) {
            return $this->errorResponse('Fail to create product image', 400);
        } catch (\Error $e) {
            echo $e->getMessage(), "\n";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $productImages = ProductImage::findOrFail($id);
            return $this->successResponse($productImages, 'Product image with ID ' . $id . ' retrieved succesfully', 200)->withHeaders(['Accept' => 'application/json', 'Content-Type' => 'application/json',]);
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
     * @param  \App\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductImageRequest $request, ProductImage $productImages, $id)
    {
        try {
            $productImages = ProductImage::findOrFail($id);
            //dd($productImages);
            $productID = Product::where('id', $request->product_id)->first();

            if ($productID->id == $productImages->product_id) {

                if ($request->name) {
                    if ($productImages->name) {
                        Storage::disk('images')->delete("/{$productID->id}/" . $productImages->name);
                        $productImages->update($request->only('product_id', 'name', 'is_main'));
                        $imageDataDecoded = base64_decode($request->name, true);
                        $imageDataExtension = getimagesizefromstring($imageDataDecoded);
                        $ext = image_type_to_extension($imageDataExtension[2]);
                        $imageName = Str::uuid() . $ext;
                        $this->uploadOne($imageDataDecoded, $productID->id, $imageName);
                        $exists = Storage::disk('images')->exists("/{$productID->id}/" . $imageName);

                        if (!$exists) {
                            return $this->errorResponse('Image fail to upload', 400);
                        }

                        $productImages->name = $imageName;
                    } else {
                        return $this->errorResponse('Product image doesnt exist', 404);
                    }
                }
                $productImages->update($request->only('is_main'));

                if ($productImages->exists()) {
                    return $this->successResponse($productImages, 'Product image with ID ' . $id .  ' updated succesfully', 200)->withHeaders(['Accept' => 'application/json', 'Content-Type' => 'application/json',]);
                } else {
                    return $this->errorResponse('Product image fail to update', 400);
                }
            } else {
                return $this->errorResponse('The specified product is not the actual image of the product', 422);
            }
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Product Image ID ' . $id . ' dont exist', 404);
        } catch (\Exception $e) {
            echo $e->getMessage(), "\n";
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $productImages = ProductImage::findOrFail($id); //Get Product Image ID
            $productID = Product::where('id', $request->product_id)->first(); //Get Product ID associated with product image

            if ($productID->id == $productImages->product_id) {
                //If that specific product image is_main equals true
                if ($productImages->is_main == true) {
                    $isNotMain = ProductImage::where(['product_id' => $request->product_id, 'is_main' => false])->first();
                    //If there is other image associated with the product. Update & delete.
                    if ($isNotMain) {
                        $isNotMain->isMain();
                        $isNotMain->save();
                        $productImages->delete();
                        Storage::disk('images')->delete("/{$productID->id}/" . $productImages->name);
                        return $this->successResponse($productImages, 'Product image with ID ' . $id . ' deleted succesfully. New main image has been selected for ' . $isNotMain->name, 200);
                    } else {
                        //If none, delete the folder too.
                        $productImages->delete();
                        Storage::disk('images')->delete("/{$productID->id}/" . $productImages->name);
                        Storage::deleteDirectory($productID->id);
                        return $this->successResponse($productImages, 'Product image with ID ' . $id . ' deleted succesfully. Your product currently doesnt have any Image attach to it.', 200);
                    }
                } else {
                    if ($productImages->name != null) {
                        $productImages->delete();
                        Storage::disk('images')->delete("/{$productID->id}/" . $productImages->name);
                        return $this->successResponse($productImages, 'Product image with ID ' . $id . ' deleted succesfully', 200);
                    }
                }
            } else {
                return $this->errorResponse('The specified product is not the actual image of the product', 422);
            }
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Product Image with ID ' . $id . ' dont exist', 404);
        } catch (\Exception $e) {
            echo $e->getMessage(), "\n";
        }
    }
}
