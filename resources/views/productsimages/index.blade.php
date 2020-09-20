@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-end mb-2">
    <a href="{{ route('productsimages.create') }}" class="btn btn-success">Add Product</a>
</div>

<div class="card card-default">
    <div class="card-header">IMAGE</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <th>Image</th>
                </thead>
                <tbody>
                    @foreach($productsimages as $image)
                    <tr>
                        <td>{{ $image->name }}</td>
                        <td><img src="{{ asset('storage/' .$image->name) }}" class="img-thumbnail" alt="product image">
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</div>


@endsection