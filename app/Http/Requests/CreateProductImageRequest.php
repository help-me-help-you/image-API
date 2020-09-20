<?php

namespace App\Http\Requests;

use App\ProductImage;
use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductImageRequest extends FormRequest
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
            'product_id' => 'bail|required|exists:App\Product,id',
            'name' => 'bail|required|base64_image',
            'is_main' => 'required|boolean' . $this->checkMain()
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
            'product_id.exists' => 'The specified product does not exist',
            'is_main.required' => 'The is_main field is required.'
        ];
    }

    private function checkMain()
    {
        try {
            if ($this->is_main) {
                $productImageHasMain = ProductImage::where(['product_id' => $this->product_id, 'is_main' => true])->count() == 1;
                $productImageHasNoMain = ProductImage::where(['product_id' => $this->product_id, 'is_main' => true])->count() == 0;
                if ($this->is_main == 1 && $productImageHasMain) {
                    $data['is_main'] = $this->is_main;
                    $isNotMain = ProductImage::where(['product_id' => $this->product_id, 'is_main' => true])->first();
                    $isNotMain->isNotMain();
                    $isNotMain->save();
                }

                if ($this->is_main == 0 && $productImageHasNoMain) {
                    $isMain = ProductImage::where(['product_id' => $this->product_id, 'is_main' => false])->first();
                    $isMain->isMain();
                    $isMain->save();
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
