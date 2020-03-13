@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col margin-tb">
            <div class="pull-left">
                <h2>Add Car</h2>
            </div>
            <div class="pull-right">
                <a href="{{route('cars.index')}}" class="btn btn-success">Back</a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Sorry</strong> There are items that need to be fixed
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{route('cars.store')}}" method="POST">
        @csrf
        <div class="form-group">
            <label for="make">Car Make</label>
            <input type="text" class="form-control" id="make" name="make" value="{{ old('make') }}">
        </div>
        <div class="form-group">
            <label for="model">Car Model</label>
            <input type="text" class="form-control" id="model" name="model" value="{{ old('model') }}">
        </div>
        <div class="form-group">
            <label for="produces_on">Date Manufactured</label>
            <input type="date" class="form-control" id="produced_on" name="produced_on" value="{{ old('produced_on') }}">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection
