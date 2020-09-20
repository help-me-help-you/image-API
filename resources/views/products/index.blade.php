@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-end mb-2">
    <a href="{{ route('products.create') }}" class="btn btn-success">Add Product</a>
</div>

<div class="card card-default">
    <div class="card-header">Products</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->slug }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{!! $product->description !!}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</div>


@endsection