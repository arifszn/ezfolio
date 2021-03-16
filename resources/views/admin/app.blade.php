@extends('admin.layouts.master')

@section('styles')
@endsection

@section('body-content')
    <div id="react-root"></div>
@endsection

@section('scripts')
    <script src="{{ asset('js/client/admin/roots/app.js') }}"></script>
@endsection