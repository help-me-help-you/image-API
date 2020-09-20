<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Storage; // import the storage facade
use App\Product;
use App\ProductImage;
use File;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('productsimages.index')->with('productsimages', ProductImage::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('productsimages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'image|max:2048|mimes:jpeg,png,jpg,gif,svg'
            ]);

        $this->validate($request, $rules);

        $data = $request->all();
        $data['name'] = $request->image->store('');

        $productImages = ProductImage::create($data);

        return response(['message' => 'Product deleted successfully']);
/*
        //$input = ProductImage::create($request->only('name'));
        $input = $request->all();

        $product_id = $request->input('product_id');  // Gets image ID from upload images form

        // Names of folders
        $productimagefolder = public_path().'/itemimages/' . $product_id;

        if( ! File::exists($productimagefolder)) {
            File::makeDirectory($productimagefolder, 0755, true);
        }

        $inputData=$request->all();
        if($request->file('name'))
        {
            $name = $request->file('name');

            // loop through each image to save and upload
            foreach ($name as $image)
            {
                if($image->isValid())
                {
                    $extension=$image->getClientOriginalExtension();
                    $image_name = $request->file('name')->getClientOriginalName();
                    $filename = $image_name . '.' .$extension;

                    // Passes filename to $inputData variable for database write
                    $inputData['name']=$filename;

                     // writes to database via eloquent model
                     ProductImage::create($inputData);
                }
            }
        }
        return back()->with('status','Images added successfully.');
        */
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product=Product::findOrFail($id);
        $productImage=ProductImage::where('product_id',$id)->get();

        return view('productsimages.show',compact('productsimages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
