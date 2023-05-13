@include('layout.errors')
<div class="form-group">
   <label for="name">name</label>
   {!! Form::text('name',null,['class'=> 'form-control']) !!}
</div>
<div class="form-group">
    <label for="name">Department</label>
    {!! Form::select('department_id',$departments,$model->yearLevel->department_id ?? null,[
        'class'=> 'form-control',
        'id' => 'department',
        'placeholder' => 'Choose Department'
        ]) !!}
 </div>
 <div class="form-group">
    <label for="name">YearLevel</label>
    {!! Form::select('year_level_id',$yearLevels,null,[
        'class'=> 'form-control',
        'id' => 'year_level',
        'placeholder' => 'Choose Year Level'
        ]) !!}
 </div>
<div class="form-group">
    <button class="btn btn-primary" type="submit">submit</button>
</div>


@push('scripts')

<script>
    $("#department").change(function(){
        console.log("dep changed")
        let departmentId = $("#department").val();
        $.ajax({
        url: "{{ url('api/year-levels') }}",
        type: 'GET',
        data: {department_id: departmentId},
        success: function(response){
            console.log(response);
            $("#year_level").html("");
            $("#year_level").append('<option value="" selected disabled>Choose Year Level</option>');
            $.each(response.data, function( index, object ) {
                $("#year_level").append('<option value="'+object.id+'">'+object.name+'</option>');
            });
        }
    })
    });

</script>

@endpush
