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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light close-btn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-sm btn-primary " id="saveBtn">Save</button>
            </div>
        </div>
        </form>
    </div>
  </div>
