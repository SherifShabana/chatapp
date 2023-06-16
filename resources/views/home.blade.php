@extends('layout.app')


@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>
                  Dashboard
              <small>Statistics</small>

          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><div href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home </div></li>
            <li class="active"> Statistics</li>
          </ol>
        </div>
      </div>
    </div>

  </section>


  <!-- Main content -->
  <section class="content">
      <div class="container-fluid">
          <div class="row">

 {{-- <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{ $stats['users'] }}</h3>

                <p>professor</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
            </div>
          </div>  --}}

              <div class="col-lg-3 col-6">
                  <!-- small box -->
                  <div class="small-box bg-warning">
                    <div class="inner">
                      <h3>{{ $stats['students'] }}</h3>

                      <p>Students</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-person-add"></i>
                    </div>
                  </div>
                </div>


              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3>{{ $stats['departments'] }}</h3>

                    <p>Department</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person-add"></i>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3>{{ $stats['yearlevels'] }}</h3>

                    <p>YearLevel</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person-add"></i>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3>{{ $stats['sections'] }}</h3>

                    <p>Section</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person-add"></i>
                  </div>
                </div>
              </div>




              {{-- <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                  <div class="inner">
                    <h3>{{ $stats['departments'] }}</h3>

                    <p>Section</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person-add"></i>
                  </div> --}}
                </div>
              </div>

          </div>
      </div>

  </section>

@endsection
