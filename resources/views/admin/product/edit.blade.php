<!-- Start Edit Product Modal -->
<div class="modal fade" id="edit-ajax-modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>

            <form method="post" action="{{ route('admin.product.update') }}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <input type="hidden" name="id" value="{{ base64_encode($product->id) }}">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control input-default location" name="title" required
                            placeholder="Enter Product Title" value="{{ $product->title }}">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control input-default slug" name="slug" required
                            placeholder="Enter Slug" value="{{ $product->slug }}">
                    </div>

                    <div class="form-group">
                        <select class="multi-select" required name="categories[]" data-live-search="true" id="edit-ajax-select"
                            data-width="100%" multiple="multiple">
                            @foreach ($categories as $category)
                                <option  {{ in_array($category->id, old('categories', [])) || $product->categories->contains($category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <textarea class="form-control input-default description" name="description" placeholder="Enter Product Description" id="description" cols="30"rows="5"> {{ $product->description }} </textarea>
                    </div>

                    <div class="custom-file">
                        <input multiple type="file" class="custom-file-input image" name="img[]" accept="image/*">
                        <label class="custom-file-label">Choose file</label>
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
<!-- End Edit Product Modal -->
