@extends('admin.layouts.app')

@section('title')
    JGJ - Blogs-Add
@endsection

@section('content')
    <blogs_add-component :card="'{{$blogs}}'" ></blogs_add-component>
@endsection
