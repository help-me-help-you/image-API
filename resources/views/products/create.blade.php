@extends('layouts.app')
@section('content')

<div class="card card-default">
    <div class="card-header">
        Create product
    </div>
    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="list-group">
                    @foreach($errors->all() as $error)
                        <li class="list-group-item text-danger font-weight-bold">
                            {{ $error}}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control" name="name">
            </div>
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" id="slug" class="form-control" name="slug">
            </div>
            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="number" id="sku" class="form-control" name="sku">
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" class="form-control" name="price" min="0.00" step="0.01">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input id="description" type="hidden" name="description">
                <trix-editor input="description"></trix-editor>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" class="form-control" name="quantity" min="0.00">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="checkbox" name="status" value=1 id="status" />
            </div>
            <div class="form-group">
                <button class="btn btn-success">Add Product</button>
            </div>

        </form>
    </div>
</div>


@endsection

@section('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.3/trix.min.js"></script>

@endsection

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.3/trix.min.css">

@endsection