@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <!-- Horizontal Flex Container -->
    <div class="flex items-center space-x-4">
        <!-- Logo -->
        <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Logo" class="h-16">

        <!-- Heading -->
        <h1 class="text-2xl font-bold text-green-700">Welcome to Client Dashboard</h1>
    </div>
</div>
@endsection
