@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #0b3d0b;
        color: #e6f0e6;
    }

    .container {
        background-color: #1a5d1a;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        margin-top: 40px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #2e7d2e;
        color: #d4f1d4;
    }

    thead th {
        background-color: #1a5d1a;
        color: #cce5cc;
        border-bottom: 2px solid #4caf50;
        padding: 10px;
        text-align: left;
    }

    tbody td {
        border-bottom: 1px solid #4caf50;
        padding: 10px;
    }

    tbody tr:hover {
        background-color: #3ea63e;
        color: #fff;
    }

    a.btn-primary,
    a.btn-warning,
    button.btn-danger {
        color: #fff;
        border: none;
    }

    a.btn-primary {
        background-color: #2e7d2e;
    }

    a.btn-primary:hover {
        background-color: #3ea63e;
    }

    a.btn-warning {
        background-color: #a67c00;
    }

    a.btn-warning:hover {
        background-color: #d4af37;
    }

    button.btn-danger {
        background-color: #a00000;
    }

    button.btn-danger:hover {
        background-color: #d43737;
    }
</style>

<div class="container">
    <h1>Products</h1>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Create Product</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>SKU</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ number_format($product->price, 2) }}</td>
                <td>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>

                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                    </form>

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection