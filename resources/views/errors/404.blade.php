@extends('errors.layout')

@section('title', 'Page Not Found')
@section('code', '404')
@section('heading', 'Lost in the Groceries?')
@section('message', "The page you're looking for has been moved or doesn't exist. Let's get you back to the fresh aisle.")

@section('illustration')
    <img src="{{ asset('images/errors/404.png') }}" alt="404 Error" class="w-full h-auto drop-shadow-2xl">
@endsection
