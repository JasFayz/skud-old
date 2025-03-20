@extends('visitor::layouts.master')

@section('title', 'Guest')
@section('content')
    <guest-list :guests="{{ json_encode($guests) }}"
                :users="{{ json_encode($users) }}"
                :filters="{{ json_encode($filters) }}"
                :companies="{{ json_encode($companies) }}"
                :zones="{{ json_encode($zones) }}"
    />
@endsection
