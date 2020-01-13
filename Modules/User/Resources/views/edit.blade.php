@extends('backend.layout.master')
@section('content')
    <div class="row mt-2 mb-2">
        <div class="col-lg-12">

        	<h2 class="relative heading-title-outer">Edit Profile
                <!--<a class="btn btn-success heading-title" href="">
                    Update
                </a>-->
            </h2>
            
        </div>
    </div>

    <!-- Sucess Message -->
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">

            {{ session()->get('success') }}

            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        </div>
    @endif
    <!-- /Sucess Message -->

<div class="card">


    <div class="card-body">

    	<form action="{{ route('user_update', [ 'id' => @$userId ]) }}" method="post" enctype='multipart/form-data'>
 
    		{{ csrf_field() }}
    		<input type="hidden" value="{{ @$userId }}" name="user_id">

	    	<div class="row">

		        <div class="col-md-6">
		        	
		        	<div class="form-group">
		        	    <label for="email">First Name:</label>
		        	    <input type="text" value="{{ @$data->first_name }}" name="first_name" class="form-control" id="first-name" required="">
		        	</div>

		        	<div class="form-group">
		        	    <label for="email">Contact Number:</label>
		        	    <input type="text" value="{{ @$data->contact_number }}" name="contact_number" class="form-control" id="contact-number" required="">
		        	</div>

		        	<div class="form-group">
		        	    <label for="email">Profile Pic:</label>
		        	    <input type="file" value="{{ @$data->profile_pic }}" name="profile_pic" class="form-control no-padding" id="profile-pic">

		        	    @if( !empty( App\User::find( @$userId )->profile->profile_pic ) )
				         <img src="{{ asset('profile_photos') }}/{{ App\User::find( @$userId )->profile->profile_pic }}" class="show-profile-image" alt="User Image">
				         @else
				         <img src="{{ asset('img/demo-user.png') }}" class="show-profile-image" alt="User Image">
				        @endif

		        	</div>

		        	<div class="form-group">
		        	    <label for="pwd">Address 2:</label>
		        	    <textarea class="form-control" name="address_2" required="">{{ @$data->address_2 }}</textarea>
		        	</div>

		        </div>

		        <div class="col-md-6">

		        	<div class="form-group">
		        	    <label for="pwd">Last Name:</label>
		        	    <input type="text" value="{{ @$data->last_name }}" name="last_name" class="form-control" id="last-name" required="">
		        	</div>

		        	<div class="form-group">
		        	    <label for="pwd">Address 1:</label>
		        	    <textarea class="form-control" name="address_1" required="">{{ @$data->address_1 }}</textarea>
		        	</div>

		        	<div class="form-group">
		        	    <label for="pwd">Bio:</label>
		        	    <textarea class="form-control" name="bio" required="">{{ @$data->bio }}</textarea>
		        	</div>

		        </div>

		        <div class="col-md-12">

		        	<div class="form-group">
		        	    <input type="submit" value="Update" class="form-control btn btn-success submit-button">
		        	</div>

		        </div>

	    	</div>

    	</form>

    </div>

</div>
@endsection
@section('scripts')
@endsection
