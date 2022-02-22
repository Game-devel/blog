@extends('admin.layouts.app')

@section('title')
    JGJ - Rubrics-Edit
@endsection

@section('content')
    <rubric_edit-component :id="{{$id}}"></rubric_edit-component>
@endsection
