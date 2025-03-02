@php
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h2>Borrowers</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Borrowers</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="card">
                    <div class="card-header">
                        <div class="row justify-content-between">
                            <div class="col-4">
                                <h3>List Borrowers <span class='badge badge-success'>Active</span></h3>
                            </div>
                            <div class="col-2 text-end">
                                <button onclick="addForm()" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="fa fa-plus-circle"></i> Add Borrower
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/tables_datatables.js -->
                        <table class="table table-stiped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Borrower</th>
                                    <th>Book</th>
                                    <th>Transaction Date</th>
                                    <th>Return Date</th>
                                    <th>Status</th>
                                    @if (Auth::user()->role == 'admin')
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@includeIf('transaction.form')
@endsection

@push('js')
    <script>
        //seting header csrf token laravel untuk semua request ajax
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        var table = $('.table').DataTable({
            pageLength: 10, // Default jumlah data
            lengthMenu: [ // Dropdown jumlah data
                [10, 25, 50, 100], // Jumlah data sebenarnya
                [10, 25, 50, 100]  // Label yang ditampilkan di dropdown
            ],
            scrollCollapse: true,
            paging: true,
            processing: true,
            autoWidth: false,
            responsive: true,
            lengthChange: true,
            processing: true,
            serverSide: true,
            dom: '<"row mb-2"<"col-sm-12 pt-3 col-md-6"l><"col-sm-12 pt-3 col-md-6"f>><"table-responsive"t><"row justify-content-between"<"col-sm-12 pt-2 pb-3 col-md-3 text-start"i><"col-sm-12 pt-2 pb-3 col-md-3 text-end"p>>',
            // dom: 'lfrtip',
            ajax: "{{ route('transactions.index') }}",
            columnDefs: [
                {
                    className: "dt-center",
                    targets: "_all"
                }
            ],
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: null,
                    searchable: false
                },
                {
                    data: 'customer',
                    name: 'customer'
                },
                {
                    data: 'book',
                    name: 'book'
                },
                {
                    data: 'transaction_date',
                    name: 'transaction_date'
                },
                {
                    data: 'return_date',
                    name: 'return_date'
                },

                @php
                    if (Auth::user()->role == 'admin') {
                        echo "
                            {
                                data: 'status',
                                name: 'status',
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            }
                        ";
                    } else {
                        echo "
                            {
                                data: 'status',
                                name: 'status',
                                mRender: function(data, type, full, meta) {
                                    if (data == 'returned') {
                                        return '<span class=\"badge bg-success\">Returned</span>';
                                    } else {
                                        return '<span class=\"badge bg-danger text-white\">Borrowed</span>';
                                    }
                                }
                            }
                        ";
                    }
                @endphp
            ]
        });


        //close modal
        $('.close-btn').click(function(e) {
            $('.modal').modal('hide')
        });


        //save data untuk edit atau create
        $('#saveBtn').click(function(e) {
            formdata = $('#form-data')[0];
            var data = new FormData(formdata);
            // console.log(data);

            // add csrf token
            data.append('_token', "{{ csrf_token() }}");

            $('#saveBtn').html('Sending..');
            $.ajax({
                    data: data,
                    url: "{{ route('transactions.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        swal.fire("Success!", data.message, "success");
                        $('#form-data').trigger("reset");
                        $('#modal-block-normal').modal('hide');
                        $('#saveBtn').html('Save');
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data.responseJSON.errors);

                        //array of errors to display html
                        errors = data.responseJSON.errors;
                        console.log(errors);

                        errorHtml = '<div class="text-right"><ul>';

                        Object.entries(errors).forEach(([field, messages]) => {
                            //if field has multiple errors
                            if (messages.length > 1) {
                                Object.entries(messages).forEach(([index, message]) => {
                                    errorHtml += '<li>' + message + '</li>';
                                })
                            } else {
                                errorHtml += '<li>' + messages + '</li>';
                            }
                        });
                        errorHtml += '</ul></div>';

                        console.log(errorHtml);

                        Swal.fire({
                            title: 'Error!',
                            html: errorHtml,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                            })
                        $('#saveBtn').html('Save Changes');
                    }
                });
        });

        //memunculkan form edit
        $('body').on('click', '.Edit', function() {
            var id = $(this).data('id');
            console.log(id);
            $.get("{{ route('customers.index') }}" + '/' + id + '/edit', function(data) {
                $('.modal-title').text('Edit Borrower' + ' - ' + data.name);
                $('#modal-block-normal').modal('show');
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#nik').val(data.nik);
                $('#email').val(data.email);
                $('#phone').val(data.phone);
                $('#address').val(data.address);
            })
        });


        //delete data
        $('body').on('click', '.Delete', function() {
            var id = $(this).data("id");

            // SweetAlert2 confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    data : {
                        _token: "{{ csrf_token() }}"
                    },
                    type: "DELETE",
                    url: "{{ route('transactions.store') }}" + '/' + id,
                    success: function(data) {
                        table.draw();
                        Swal.fire(
                            'Deleted!',
                            'Your Borrower has been deleted.',
                            'success'
                        );
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        Swal.fire(
                            'Error!',
                            'There was an error deleting the Borrower.',
                            'error'
                        );
                    }
                });
                }
            });
        });

        //memunculkan form add
        function addForm() {
            $("#modal-block-normal").modal('show');
            $('#id').val('');
            $('.modal-title').text('Add Customer');
            $('#modal-block-normal form')[0].reset();
            $('#modal-block-normal [name=name]').focus();
        }

        $('body').on('click', '.Change', function() {
            var id = $(this).data('id');

            // SweetAlert2 confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
                        url: '/transactions/' + id + '/change-status',
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,

                        success: function(data) {
                            swal.fire("Success!", data.message, "success");
                            $('#form-data').trigger("reset");
                            $('#modal-block-normal').modal('hide');
                            $('#saveBtn').html('Save');
                            table.draw();
                        },
                        error: function(data) {
                            console.log('Error:', data.responseJSON.errors);

                            //array of errors to display html
                            errors = data.responseJSON.errors;
                            console.log(errors);

                            errorHtml = '<div class="text-right"><ul>';

                            Object.entries(errors).forEach(([field, messages]) => {
                                //if field has multiple errors
                                if (messages.length > 1) {
                                    Object.entries(messages).forEach(([index, message]) => {
                                        errorHtml += '<li>' + message + '</li>';
                                    })
                                } else {
                                    errorHtml += '<li>' + messages + '</li>';
                                }
                            });
                            errorHtml += '</ul></div>';

                            console.log(errorHtml);

                            Swal.fire({
                                title: 'Error!',
                                html: errorHtml,
                                icon: 'error',
                                confirmButtonText: 'Ok'
                                })
                            $('#saveBtn').html('Save Changes');
                        }
                    });
                }
            });

        });
    </script>
@endpush
