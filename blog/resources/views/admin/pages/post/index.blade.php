@extends('layouts.app')

@section('title', 'Post')

@section('page_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Post</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Post</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible text-sm">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Error!</h5>
        {{ $message }}
    </div>
@elseif ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible text-sm">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        {{ $message }}
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('post.create') }}" class="btn btn-dark"><i class="fas fa-plus pr-1"></i> Add New</a>
            </div>
            <div class="card-body">
                <table class="table table-hover data-table">
                    <thead>
                        <tr>
                            <th width="30px">#</th>
                            <th>Title</th>
                            <th width="130px">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('stylesheets')
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush

@push('scripts')
<!-- DataTables  & Plugins -->
<script src="https://adminlte.io/themes/v3/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/jszip/jszip.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/pdfmake/pdfmake.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/pdfmake/vfs_fonts.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script type="text/javascript">
    $(function(){
        // Token
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Data Tables
        var table = $(".data-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('post.index') }}",
            columns: [
                { data: "id", name: "id" },
                { data: "title", name: "title" },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false
                },
            ],
        });

        // Delete
        $('body').on('click', '.delete', function (){
            var get_id = $(this).data("id");
            var result = confirm("Are You sure want to delete !");

            if(result){
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('post.store') }}"+'/'+get_id,
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }else{
                return false;
            }
        });
    });
</script>
@endpush
