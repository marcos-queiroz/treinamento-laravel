@extends('layouts.app')

@section('content')
    <login-component csrf-token="{{ @csrf_token() }}"></login-component>
@endsection
