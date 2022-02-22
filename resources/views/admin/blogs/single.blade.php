@extends('admin.layouts.app')

@section('title')
    JGJ - Blogs-Single
@endsection

@section('content')
    <blogs_single-component :id="{{$id}}"></blogs_single-component>
@endsection
