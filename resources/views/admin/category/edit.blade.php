<!-- Start Edit Category Modal -->
<div class="modal fade" id="edit-ajax-modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>

            <form method="post" action="{{ route('admin.category.update') }}">
                @csrf
                @method('put')
                <input type="hidden" name="id" value="{{ base64_encode($category->id) }}">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control input-default category" name="title" required
                            placeholder="Enter Category" value="{{ $category->title }}">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control input-default slug" name="slug" required
                            placeholder="Enter Slug" value="{{ $category->slug }}">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update changes</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- End Edit Category Modal -->
