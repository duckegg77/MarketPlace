@extends('layouts.app')

@section('content')
    <h1>Marketplace Search</h1>
    <p class="muted">No JavaScript. Server-rendered search, category filtering, and sorting.</p>

    <form method="GET" action="{{ route('marketplace.index') }}" class="filters">
        <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Search by name, SKU, description">

        <select name="category_id">
            <option value="">All categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string)$filters['category_id'] === (string)$category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select name="sort">
            <option value="newest" @selected($filters['sort'] === 'newest')>Newest</option>
            <option value="name" @selected($filters['sort'] === 'name')>Name</option>
            <option value="price_low" @selected($filters['sort'] === 'price_low')>Price low-high</option>
            <option value="price_high" @selected($filters['sort'] === 'price_high')>Price high-low</option>
        </select>

        <button type="submit">Search</button>
    </form>

    <div class="grid products">
        @forelse($products as $product)
            <article class="card">
                <div class="pill">{{ $product->category?->name ?? 'Uncategorized' }}</div>
                <h3>{{ $product->name }}</h3>
                <p class="muted">SKU: {{ $product->sku }}</p>
                <p>{{ \Illuminate\Support\Str::limit($product->description, 120) }}</p>
                <p class="price">${{ number_format($product->price_cents / 100, 2) }}</p>
            </article>
        @empty
            <p>No products found for current search.</p>
        @endforelse
    </div>

    <div style="margin-top:1rem;">{{ $products->links() }}</div>
@endsection
