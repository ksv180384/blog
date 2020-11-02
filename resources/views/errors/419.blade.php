@extends('errors::minimal')

@section('title', __('Страница больше не действительна'))
@section('code', '419')
@section('message', __($exception->getMessage() ? $exception->getMessage() : 'Страница больше не действительна'))