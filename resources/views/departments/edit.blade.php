@extends('layout.app')
@section('page_title')
       create departments
@endsection


@section('content')
 <section class="box">
    <div class="box-header with-boder">
             <h3 class="box-title">create departments</h3>
       <div class="box-tools pull-right">
             {{-- <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="collapse"><i class="fa fa-minus"></i></button>

             <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="remove">
              <i class="fa fa-times"></i></button> --}}
       </div>
    </div>

    <div class="box-body">
        <form action="{{ route('department.update',$department) }}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('PATCH')
            @include('flash::message')
            {{-- @include('partials.validation_errors') --}}                            {{-- take a look --}}
            @include('departments.form')
        </form>
    </div>
 </section>
 @endsection
