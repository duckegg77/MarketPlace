@extends('layouts.app')

@section('content')
    <h1>Welcome to MarketPlace</h1>
    <p>Black and white, JavaScript-free marketplace experience.</p>
    <p><a href="{{ route('marketplace.index') }}">Browse marketplace</a></p>
@endsection
