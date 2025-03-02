@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h2>Books</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Books</li>
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
                                <h3>List Books</h3>
                            </div>
                            <div class="col-2 text-end">
                                <button onclick="addForm()" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="fa fa-plus-circle"></i> Add Book
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
                                    <th>Title</th>
                                    <th>Genre</th>
                                    <th>Author</th>
                                    <th>publisher</th>
                                    <th>Stock</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@includeIf('book.form')
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
            ajax: "{{ route('books.index') }}",
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
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'category',
                    name: 'category'
                },
                {
                    data: 'author',
                    name: 'author'
                },
                {
                    data: 'publisher',
                    name: 'publisher'
                },
                {
                    data: 'stock',
                    name: 'stock'
                },
                {
                    data: 'image',
                    name: 'image',
                    mRender: function(data, type, full, meta) {
                        if (data == null || data == '') {
                            return "-";
                        }

                        return '<img src=" {{ asset("image_book") }}/' + data + '" width="auto" height="70px" style="object-fit:cover"/>';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
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
                    url: "{{ route('books.store') }}",
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
            $.get("{{ route('books.index') }}" + '/' + id + '/edit', function(data) {
                $('.modal-title').text('Edit Book' + ' - ' + data.title);
                $('#modal-block-normal').modal('show');
                $('#id').val(data.id);
                $('#title').val(data.title);
                $('#author').val(data.author);
                $('#publisher').val(data.publisher);
                $('#category_id').val(data.category_id);
                $('#year').val(data.year);
                $('#isbn').val(data.isbn);
                $('#description').val(data.description);
                $('#stock').val(data.stock);

                //add dom
                $('#previewImage').attr('src', '{{ asset("image_book") }}/' + data.image);
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
                    url: "{{ route('books.store') }}" + '/' + id,
                    success: function(data) {
                        table.draw();
                        Swal.fire(
                            'Deleted!',
                            'Your book has been deleted.',
                            'success'
                        );
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        Swal.fire(
                            'Error!',
                            'There was an error deleting the book.',
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
            $('.modal-title').text('Add Book');
            $('#modal-block-normal form')[0].reset();
            $('#modal-block-normal [name=name]').focus();
        }
    </script>
@endpush
