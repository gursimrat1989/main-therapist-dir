@extends('backend.layout.master')
@section('content')
    <div class="row mt-2 mb-2">
        <div class="col-lg-12">

        	<h2 class="relative heading-title-outer">Newsletter Lists
                <a class="btn btn-success heading-title" href="{{ route('create_subs_list') }}">
                    Add List
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
                    <th>name</th>
                    <th width="100px">Action</th>
                </tr>
            </thead>

            <tbody>
            </tbody>

            </table>

        </div>

    </div>


    <!-- View Modal -->

    <a style="display: none;" type="button" class="btn btn-info btn-lg view-list" data-toggle="modal" data-target="#view-modal"></a>

    <div id="view-modal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title title">Newsletter List</h4>
          </div>
          <div class="modal-body">
            
            <div class="row">

                <div class="col-md-12">

                    <p><span class="description">N/A</span></p>

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
    ajax: "{{ route('subs_list_list') }}",

    columns: [

        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'list_name', name: 'list_name'},
        {data: 'action', name: 'action', orderable: false, searchable: false},

    ]

});

});

/* Delete user */
$(document).on('click', '.delete-list', function(){

    var $id = $(this).attr('data');

    var isDelete=confirm('Are you sure you want to delete this list?');

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
                    $('.'+index).html( item );
                }

            });

            //Show modal
            $('.view-list').trigger('click');
        }
    })

})

</script>


@endsection
