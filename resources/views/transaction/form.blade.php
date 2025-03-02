@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!-- Modal -->
<div class="modal fade" id="modal-block-normal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form  onSubmit="JavaScript:submitHandler()"  action="javascript:void(0)" method="POST" id="form-data">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                {{-- @csrf --}}
                <input type="hidden" name="id" id="id" class="form-control">

                @if (Auth::user()->role == 'admin')
                    <input type="hidden" name="auth" id="auth" value="admin" class="form-control">
                    <div class="form-group">
                        <label for="name">Custumer</label>
                        <select name="customer_id" id="customer_id" class="form-control">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-group">
                    <label for="book_id">Book</label>
                    <select name="book_id" id="book_id" class="form-control">
                        <option value="">Select Book</option>
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="transaction_date">Transaction Date</label>
                    <input type="date" class="form-control" id="transaction_date" name="transaction_date" placeholder="Enter Transaction Date">
                </div>

                <div class="form-group">
                    <label for="return_date">Return Date</label>
                    <input type="date" class="form-control" id="return_date" name="return_date" placeholder="Enter Return Date">
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
