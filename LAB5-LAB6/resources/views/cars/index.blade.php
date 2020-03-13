@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col my-4">
                <div class="float-left">
                    <h2>All Cars</h2>
                </div>
                <div class="float-right">
                    <a href="{{route('cars.create')}}" class="btn btn-success">Create a new car</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col">

                @if($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{$message}}</p>
                    </div>
                @endif
                <table class="table">
                    <tr>
                        <th class="text-uppercase">ID</th>
                        <th class="text-uppercase">Make</th>
                        <th class="text-uppercase">Model</th>
                        <th class="text-uppercase">Produced</th>
                        <th class="text-uppercase">Action</th>
                    </tr>
                    @foreach($cars as $car)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$car->make}}</td>
                            <td>{{$car->model}}</td>
                            <td>{{$car->produced_on}}</td>
                            <td>
                                <form action="{{route('cars.destroy',$car->id)}}" method="POST">
                                    <a href="{{route('cars.show',$car->id)}}" class="btn btn-info">Show</a>
                                    <a href="{{route('cars.edit',$car->id)}}" class="btn btn-primary">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
                {{ $cars->links() }}
            </div>
        </div>
    </div>
@endsection

