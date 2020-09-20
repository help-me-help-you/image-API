@extends('layouts.app')
@section('Product')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('products.index') }}">Back</a>
            </div>
        </div>
    </div>
   
    <div class="row">
      
    @foreach($productImage as $image)
  <img src="{{ $image->name }}">
@endforeach
        
    </div>


@endsection