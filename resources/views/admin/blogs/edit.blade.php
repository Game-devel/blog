@extends('admin.layouts.app')

@section('title')
    JGJ - Blogs-Edit
@endsection

@section('content')
    <blogs_edit-component :id="{{$id}}"></blogs_edit-component>
@endsection
