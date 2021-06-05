<form method="POST" id="editSubscriptionPriceForm">
    @method('put')
    <div class="modal fade" tabindex="-1" role="dialog" id="editSubscriptionPriceModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('titles.edit_subscription_price')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inpEditSubPriceModalCustomer">@lang('fields.customer')</label>
                        <input type="text" id="inpEditSubPriceModalCustomer" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inpEditSubPriceModalService">@lang('fields.service')</label>
                        <input type="text" id="inpEditSubPriceModalService" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inpEditSubPriceModalEditPrice">@lang('fields.price')</label>
                        <input type="number" name="price" id="inpEditSubPriceModalEditPrice" class="form-control" step="0.01">
                    </div>

                    <div class="form-group mb-0">
                        <label for="txtEditSubPriceModalDescription">@lang('fields.description')</label>
                        <textarea name="description" id="txtEditSubPriceModalDescription" class="form-control" rows="3"></textarea>
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
