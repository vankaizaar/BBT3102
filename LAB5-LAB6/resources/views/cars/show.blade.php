@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col margin-tb">
            <div class="pull-left">
                <h2>Show Car</h2>
            </div>
            <div class="pull-right">
                <a href="{{route('cars.index')}}" class="btn btn-success">Back</a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Make:</strong>

                {{ $car->make }}

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Model:</strong>

                {{ $car->model }}

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">
                <strong>Manufactured:</strong>
                {{ $car->produced_on }}

            </div>

        </div>

    </div>
@endsection
