@extends('layout.app')
@section('page_title')
Student
@endsection
@section('small_title')
    list of Student
@endsection


@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Student</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Student</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="card">
        <div class="card-header">Student</div>

        <div class="card-body">
            <a href="{{route('student.create')}}" class="btn btn-primary mb-2">new Student</a>
            <!-- Button trigger modal -->
<!-- Button trigger modal -->
<button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#exampleModal">
    Import Students
  </button>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Import Students</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {!! Form::open([
            'route' => 'student.import',
            'method' => 'post',
            'files' => true
        ]) !!}
        <div class="modal-body">

            <input type="file" name="students_file">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Import</button>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
             @if (count($records))
             <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>section</th>
                        <th>Year Level</th>
                        <th>Department</th>
                        <th class="text-center">edit</th>
                        <th class="text-center">delete</th>
                    </tr>
                  </thead>
                    <tbody>
                    @foreach ($records as $record)
                      <tr>
                        {{-- <td>{{$loop->iteration}}</td> --}}
                        <td>{{$record->id}}</td>
                        <td>{{$record->name}}</td>
                        <td>{{$record->section->name ?? ""}}</td>
                        <td>{{$record->section->yearLevel->name ?? ""}}</td>
                        <td>{{$record->section->yearLevel->department->name ?? ""}}</td>

                        <td class="text-center">
                            <a href="{{ route('student.edit',$record->id) }}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                        </td>
                        <td class="text-center">
                            {!! Form::open([
                                'route' => ['student.destroy', $record->id],
                                'method' => 'delete'
                            ])
                            !!}
                            <button type="submit" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash"></i>
                            </button>
                            {!! form::close() !!}
                        </td>

                      </tr>
                    @endforeach
                    </tbody>
                </table>
             </div>
                 <div class="text-center">
                        {!! $records->links("pagination::bootstrap-4") !!}
                 </div>
           @else
              <div class="alert alert-danger" role="alert">
                   no data found
              </div>

           @endif
        </div>

    </div>
</div>
@endsection
