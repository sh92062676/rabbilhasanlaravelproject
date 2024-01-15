@extends('layout.app')
@section('title','Blogs')
@section('content')
<div class="container-fluid jumbotron mt-5 ">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6  text-center">
            <h1 class="page-top-title mt-3">- ব্লগ পড়ুন  -</h1>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        @foreach ($blogs as $blog)
            
        <div class="col-md-4 p-1 ">
            <div class="card">
                    <img class="w-100" src="{{$blog->img}}" alt="Card image cap">
                    <div class="w-100 p-4">
                        <h5 class="blog-card-title mt-2">{{$blog->caption}} </h5>
                        <h6 class="blog-card-subtitle p-0 ">{{$blog->des}}</h6>
                        <h6 class="blog-card-date "> <i class="far fa-clock"></i> {{$blog->date}}</h6>
                        <a href="{{$blog->link}}" class="normal-btn-outline mt-2 mb-4 btn">আরো পড়ুন </a>
                    </div>

            </div>
        </div>

        @endforeach

    </div>
</div>
@endsection