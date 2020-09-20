@extends('layouts.app')
   
@section('Product')


        <div class="d-flex justify-content-end mb-2">
                <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
            </div>

        <div class="card card-default">
            <div class="card-header">Edit Product</div>
        </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
  
    <form action="{{ route('products.update',$product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
   
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $product->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <label for="Product Description">Product Description:</label>
<input id="x" type="hidden" name="detail" value=" {!! $product->detail !!}">
  <trix-editor input="x"></trix-editor>
            </div>
        </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <label for="Image">Product Image:</label>
                <input type="file" name="image" class="form-control" placeholder="Name" id="image">
                <img src="{{ asset('storage/' . $product->image) }}" style="width: 100%" alt="product image">
            </div>
        </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
   
    </form>
    @endsection

@section('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.3/trix.min.js"></script>

@endsection

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.3/trix.min.css">

@endsection