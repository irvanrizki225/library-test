<!-- Normal Block Modal -->

<!-- END Normal Block Modal -->

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
                <div class="form-group">
                    <label for="title">title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title">
                </div>
                <div class="form-group">
                    <label for="author">author</label>
                    <input type="text" class="form-control" id="author" name="author" placeholder="Enter author">
                </div>
                <div class="form-group">
                    <label for="publisher">publisher</label>
                    <input type="text" class="form-control" id="publisher" name="publisher" placeholder="Enter publisher">
                </div>
                <div class="form-group">
                    <label for="year">year</label>
                    <input type="text" class="form-control" id="year" name="year" placeholder="Enter year">
                </div>
                <div class="form-group">
                    <label for="isbn">isbn</label>
                    <input type="text" class="form-control" id="isbn" name="isbn" placeholder="Enter isbn">
                </div>
                <div class="form-group">
                    <label for="description">description</label>
                    <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="text" class="form-control" id="stock" name="stock" placeholder="Enter stock">
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <span>*Max 2MB</span><br>
                    <img src="" alt="" id="previewImage" style="max-width: 100px; max-height: 100px;">
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
