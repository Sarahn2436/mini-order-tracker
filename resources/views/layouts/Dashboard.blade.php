@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>{{ $pageTitle }}</h1>
    <p>You are sharing this Dashboard with {{ $OnlineCount }} others.</p>
@endsection