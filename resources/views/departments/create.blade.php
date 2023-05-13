@extends('layout.app')
@section('page_title')

@endsection


@section('content')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Departments</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Departments</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="card">
        <div class="card-header">Departments</div>

        <div class="card-body">
            {!! Form::model($model,[
                'route' => 'department.store',
                'method' => 'post'
            ]) !!}
            @include('departments.form')

            {!! form::close() !!}
        </div>

    </div>
</div>

@endsection
