@include('layout.errors')
<div class="form-group">
   <label for="name">name</label>
   {!! Form::text('name',null,['class'=> 'form-control']) !!}
</div>
<div class="form-group">
    <button class="btn btn-primary" type="submit">submit</button>
    {{-- {!! form::close() !!} --}}
</div>
