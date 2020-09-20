@extends('layouts.app')
@section('content')

<div class="card card-default">
    <div class="card-header">
        Create Image
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

        <form action="{{ route('productsimages.store') }}" method="POST" enctype="multipart/form-data" multiple>
            @csrf
            <div class="form-group">
                <label for="Image">Product Image:</label>
                <input type="file" name="name" class="form-control" placeholder="upload your image" id="name" multiple>
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