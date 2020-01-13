@extends('backend.layout.master')
@section('content')
    <div class="row mt-2 mb-2">
        <div class="col-lg-12">

        	<h2 class="relative heading-title-outer">Edit Article
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

    	<form action="{{ route('article_update', [ 'id' => @$data->id ]) }}" method="post" enctype='multipart/form-data'>
 
    		{{ csrf_field() }}

	    	<div class="row">

		        <div class="col-md-12">
		        	
		        	<div class="form-group">
		        	    <label for="email">Title:</label>
		        	    <input type="text" value="{{ @$data->title }}" name="title" class="form-control" id="title" required="">
		        	</div>

                    <!--<div class="form-group">
                        <label for="pwd">Select Category:</label>
                        {{ Form::select('category_id', $blogCategories, @$data->category_id, ['class' => 'form-control']) }}
                    </div>-->

                    <div class="form-group">
                    <label for="email">Select Category:</label>
                        <select data-placeholder="Blog Categories" name='category_ids[]' class="chosen-select form-control" multiple tabindex="6" required="">

                        @foreach( $blogCategories as $catId => $catName )

                                @if( in_array( $catId, $savedCategoriesIds ) )

                                    <option value="{{ $catId }}" selected=''>{{ $catName }}</option>

                                @else

                                    <option value="{{ $catId }}">{{ $catName }}</option>
    
                                @endif

                        @endforeach
                        
                        </select>
                    </div>

		        	<div class="form-group">
		        	    <label for="pwd">Description:</label>
		        	    <textarea class="form-control gss-rich-editor" name="description" required="">{{ @$data->description }}</textarea>
		        	</div>

                    <div class="form-group">
		        	    <label for="pwd">Featured Image:</label>
		        	    <input type="file" value="{{ @$data->featured_image }}" name="featured_image" class="form-control no-padding" id="featured-image">
		        	</div>

                    <div class="form-group">
		        	    <label for="pwd">Is Featured:</label>

                        @if( @$data->is_featured )

                            <input type="checkbox" value='1'  name="is_featured" class="" checked="">

                        @else

                            <input type="checkbox" value='1'  name="is_featured" class="">

                        @endif

		        	    
		        	</div>

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
