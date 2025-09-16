@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #0b3d0b;
        color: #e6f0e6;
    }

    .form-container {
        background-color: #1a5d1a;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        max-width: 600px;
        margin: 40px auto;
        color: #e6f0e6;
    }

    label {
        color: #cce5cc;
    }

    .form-control {
        background-color: #2e7d2e;
        border: 1px solid #4caf50;
        color: #e6f0e6;
    }

    .form-control:focus {
        background-color: #3ea63e;
        color: #fff;
        border-color: #a5d6a7;
        box-shadow: 0 0 5px #a5d6a7;
    }

    .btn-primary {
        background-color: #2e7d2e;
        border-color: #2e7d2e;
    }

    .btn-primary:hover {
        background-color: #3ea63e;
        border-color: #3ea63e;
    }

    .btn-secondary {
        background-color: #4a6f4a;
        border-color: #4a6f4a;
        color: #e6f0e6;
    }

    .btn-secondary:hover {
        background-color: #5e885e;
        border-color: #5e885e;
        color: #fff;
    }

    .invalid-feedback {
        color: #ffb3b3;
    }
</style>

<div class="form-container">
    <h1>Edit Product</h1>

    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="sku" class="form-label">SKU</label>
            <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" required>
            @error('sku')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" min="0" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
            @error('price')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary" type="submit">Update</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection