@extends('app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>
            @if($errors->any())
                <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                    {{ $errors->first() }}
                </div>
            @endif
            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Password</label>
                    <input type="password" name="password" class="w-full border rounded p-2">
                </div>
                <button class="w-full bg-blue-500 text-white p-2 rounded">Login</button>
            </form>
        </div>
    </div>
@endsection