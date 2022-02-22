@extends('admin.layouts.app')

@section('title')
    JGJ - Blogs-List
@endsection

@section('content')
    <blogs_list-component :current_page="'{{$page}}'" :rubric_id="'{{$rubric_id}}'"></blogs_list-component>
@endsection
