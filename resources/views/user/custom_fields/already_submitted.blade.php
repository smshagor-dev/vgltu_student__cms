@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-body">
                        <h1 class="text-danger display-4">No Data Available Right Now</h1>
                        <h2 class="lead">
                            You have already filled out this form,</br><strong>or</strong> </br>Currently don't have any form for you.
                        </h2>
                        <div class="mt-4">
                            <a href="/user/custom-fields/data" class="btn btn-primary btn-lg">View Data</a>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
