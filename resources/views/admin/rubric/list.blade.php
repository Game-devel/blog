@extends('admin.layouts.app')

@section('title')
    JGJ - Rubrics-List
@endsection

@section('content')
    <rubric_list-component :current_page="{{$page}}"></rubric_list-component>
@endsection
