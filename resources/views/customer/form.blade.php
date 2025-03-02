<!-- Normal Block Modal -->

<!-- END Normal Block Modal -->

<!-- Modal -->
<div class="modal fade" id="modal-block-normal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form  onSubmit="JavaScript:submitHandler()"  action="javascript:void(0)" method="POST" id="form-data">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                {{-- @csrf --}}
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

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light close-btn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-sm btn-primary " id="saveBtn">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>

@push('js')
    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const togglePasswordButton = document.getElementById('togglePassword_' + fieldId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePasswordButton.classList.remove('fa-eye');
                togglePasswordButton.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                togglePasswordButton.classList.remove('fa-eye-slash');
                togglePasswordButton.classList.add('fa-eye');
            }
        }
    </script>
@endpush
