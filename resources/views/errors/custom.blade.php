@extends('errors.master')

@section('title', __('Error'))
@section('code', !empty($status) ? $status : '500')
@section('message', !empty($message) ? $message :  __('Server Error'))
