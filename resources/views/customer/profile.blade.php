@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h2>Customer</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Customer</li>
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
                                <h3 id="title">Detail Customer </h3><span id="status" class='badge badge-success'></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="id" id="id" class="form-control">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" placeholder="Enter NIK">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone">
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                    <i class="fa fa-eye" id="togglePassword_password"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Password Confirmation</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter password confirmation">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                                    <i class="fa fa-eye" id="togglePassword_password_confirmation"></i>
                                </button>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">

                            <button type="submit" class="btn btn-sm btn-primary " id="saveBtn">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@includeIf('customer.form')
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

        //binding data to form detail
        $(document).ready(function() {
            var url = new URL(window.location.href);;
            var pathSegments = url.pathname.split('/');
            var id = pathSegments[pathSegments.length - 1]; // Get the last segment
            $.get("{{ route('customers.profile') }}", function(data) {
                $('#title').text('Detail Customer' + ' - ' + data.name);
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#nik').val(data.nik);
                $('#email').val(data.email);
                $('#phone').val(data.phone);
                $('#address').val(data.address);
            })
        })


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
                    url: "{{ route('customers.store') }}",
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


    </script>
@endpush
