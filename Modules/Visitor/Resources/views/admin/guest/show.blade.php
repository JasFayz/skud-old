@extends('visitor::layouts.master')

@section('content')
    <guest-info :guest="{{ json_encode($guest) }}"/>
@endsection
