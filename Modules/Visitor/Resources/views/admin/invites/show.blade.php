@extends('visitor::layouts.master')

@section('title', 'Приглашение')

@section('content')
    <invite-info :invite="{{ json_encode($invite) }}"/>
@endsection
