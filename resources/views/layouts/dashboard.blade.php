@extends('app')
@section('content')
    @include('layouts.menus.navbar')
    @include('layouts.menus.sidebar')
    <div class="p-4 sm:ml-64 mt-[60px]">
        @yield('dashboard')
    </div>
@endsection