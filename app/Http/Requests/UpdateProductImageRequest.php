<?php

namespace App\Http\Requests;

use App\Product;
use App\ProductImage;
use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductImageRequest extends FormRequest
{
    use ApiResponser;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|exists:App\Product,id',
            'name' => 'sometimes|required|base64_image',
            'is_main' => 'required|boolean' . $this->checkUpdateMain(),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_id.exists' => 'The specified product does not exist'
        ];
    }

    private function checkUpdateMain()
    {
        try {
            $productImageID = $this->route('productsimage');
            $productImage = ProductImage::findOrFail($productImageID);
            $productID = Product::where('id', $this->product_id)->first();
            if ($productID->id == $productImage->product_id) {
                if ($this->is_main == 1 && $productImage->is_main == 0) {
                    $productImageHasMain = ProductImage::where(['product_id' => $this->product_id, 'is_main' => true])->first();
                    if ($productImageHasMain) {
                        $productImageHasMain->isNotMain();
                        $productImageHasMain->save();
                        ProductImage::findOrFail($productImageID)->update(['is_main' => true]);
                    } else {
                        ProductImage::findOrFail($productImageID)->update(['is_main' => true]);
                    }
                }

                if ($this->is_main == 0 && $productImage->is_main == 1) {
                    $productImageHasNoMain = ProductImage::where(['product_id' => $this->product_id, 'is_main' => false])->first();
                    if ($productImageHasNoMain) {
                        $productImageHasNoMain->isMain();
                        $productImageHasNoMain->save();
                        ProductImage::findOrFail($productImageID)->update(['is_main' => false]);
                    } else {
                        return $this->errorResponse('At least 1 main image has to be assigned', 400);
                    }
                }
            }
        } catch (\ModelNotFoundException $exception) {
            return $this->errorResponse('Product Image ID ' . $this->product_id . ' dont exist', 404);
        } catch (\Exception $e) {
            echo $e->getMessage(), "\n";
        } catch (\Error $e) {
            echo $e->getMessage(), "\n";
        }
    }
}
