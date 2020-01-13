@extends('backend.layout.master')
@section('content')
    <div class="row mt-2 mb-2">
        <div class="col-lg-12">

        	<h2 class="relative heading-title-outer">Users
                <a class="btn btn-success heading-title" href="{{ route('create_user') }}">
                    Add Users
                </a>
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

        <div class="table-responsive">

            <table class="table table-bordered data-table" id="beauti-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th width="100px">Action</th>
                </tr>
            </thead>

            <tbody>
            </tbody>

            </table>

        </div>

    </div>


    <!-- View Modal -->

    <a style="display: none;" type="button" class="btn btn-info btn-lg view-user" data-toggle="modal" data-target="#view-modal"></a>

    <div id="view-modal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">User View</h4>
          </div>
          <div class="modal-body">
            
            <div class="row">

                <div class="col-md-4">
                    <img class="profile_pic show-profile-image" src="{{ asset('img/demo-user.png') }}">
                </div>

                <div class="col-md-8">

                    <p>User Name: <span class="name">N/A</span></p>
                    <p>First Name: <span class="first_name">N/A</span></p>
                    <p>Last Name: <span class="last_name">N/A</span></p>
                    <p>Email: <span class="email">N/A</span></p>
                    <p>Contact Number: <span class="contact_number">N/A</span></p>
                    <p>Address: <span class="address_1">N/A</span></p>
                    <p>Bio: <span class="bio">N/A</span></p>

                </div>

            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

    <!-- /View Modal -->

</div>
@endsection
@section('scripts')

<script type="text/javascript">

/* Datatables */
$(function () {

var table = $('.data-table').DataTable({

    processing: true,
    serverSide: true,
    ajax: "{{ route('users_list') }}",

    columns: [

        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'name', name: 'name'},
        {data: 'email', name: 'email'},
        {data: 'action', name: 'action', orderable: false, searchable: false},

    ]

});

});

/* Delete user */
$(document).on('click', '.delete-user', function(){

    var $id = $(this).attr('data');

    var isDelete=confirm('Are you sure you want to delete the user?');

    if( isDelete )
    {
        $(this).parent().parent().find('form').submit();
    }
})

/* Show view ajax call */
$(document).on('click', '.show-view', function(){

    var $url = $(this).attr('data');

    $.ajax({
        url: $url,
        type: 'get',
        success: function(data)
        {
            //User data
            jQuery.each(data, (index, item) => {
                
                if( item != null )
                {
                    $('.'+index).text( item );
                }

            });

            //Profile data
            if( data.profile )
            {

                jQuery.each(data.profile, (index, item) => {
                
                if( item != null && item != 'profile_pic' )
                {
                    $('.'+index).text( item );
                }

                });

            
            

                //Profile pic
                if( data.profile.profile_pic != null )
                {
                    $('.profile_pic').attr( 'src', '{{ asset("profile_photos") }}/'+data.profile.profile_pic );
                }
                else
                {
                    $('.profile_pic').attr( 'src', '{{ asset("img/demo-user.png") }}' );
                }

            }

            //Show modal
            $('.view-user').trigger('click');
        }
    })

})

</script>


@endsection
