<form method="POST" id="editPaymentPriceForm">
    @method('put')
    <div class="modal fade" tabindex="-1" role="dialog" id="editPaymentPriceModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('titles.get_payment')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inpEditPrice">@lang('fields.price')</label>
                        <input type="number" name="price" id="inpEditPrice" class="form-control" step="0.01">
                    </div>

                    <div class="form-group">
                        <label for="txtDescription">@lang('fields.description')</label>
                        <textarea name="description" id="txtDescription" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('titles.cancel')</button>
                    <button type="submit" class="btn btn-primary">@lang('fields.send')</button>
                </div>
            </div>
        </div>
    </div>
</form>
