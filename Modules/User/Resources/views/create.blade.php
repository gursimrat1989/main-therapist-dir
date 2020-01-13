@extends('backend.layout.master')
@section('content')
    <div class="row mt-2 mb-2">
        <div class="col-lg-12">

        	<h2 class="relative heading-title-outer">Create User
                <!--<a class="btn btn-success heading-title" href="">
                    Update
                </a>-->
            </h2>
            
        </div>
    </div>

    <!-- Errors -->
	@if ($errors->any())
	    <div class="alert alert-danger alert-dismissible fade show">

	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>

	        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

	    </div>
	@endif
	<!-- /Errors -->

<div class="card">

    <div class="card-body">

    	<form action="{{ route('store_user') }}" method="post" enctype='multipart/form-data' autocomplete="off">
 
    		{{ csrf_field() }}

	    	<div class="row">

		        <div class="col-md-6">

		        	<div class="form-group">
		        	    <label for="pwd">Name:</label>
		        	    <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="name" required="">
		        	</div>

		        	<div class="form-group">
		        	    <label for="pwd">Email:</label>
		        	    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required="">
		        	</div>

		        	<div class="form-group">
		        	    <label for="pwd">Password:</label>
		        	    <input type="password" name="password" class="form-control">
		        	</div>

		        	<div class="form-group">
		        	    <label for="pwd">Confirm Password:</label>
		        	    <input type="password" name="password_confirmation" class="form-control">
		        	</div>

		        </div>

		        <div class="col-md-12">

		        	<div class="form-group">
		        	    <input type="submit" value="Add User" class="form-control btn btn-success submit-button">
		        	</div>

		        </div>

	    	</div>

    	</form>

    </div>

</div>
@endsection
@section('scripts')
@endsection
