@extends('visitor::layouts.master')


@section('title', 'Приглашения')
@section('content')
    <invite-list
        :invites="{{ json_encode($invites) }}"
        :users="{{ json_encode($users) }}"
        :filters="{{ json_encode($filters) }}"
        :companies="{{ json_encode($companies) }}"
        :zones="{{ json_encode($zones) }}"
    />
@endsection
