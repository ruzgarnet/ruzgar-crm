<form method="POST" id="renewalSubscriptionForm">
    @method('put')
    <div class="modal fade" tabindex="-1" role="dialog" id="renewalSubscriptionModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('titles.renewal_subscription')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="@lang('titles.close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inpRenewalSubModalCustomer">@lang('fields.customer')</label>
                        <input type="text" id="inpRenewalSubModalCustomer" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inpRenewalSubModalService">@lang('fields.service')</label>
                        <input type="text" id="inpRenewalSubModalService" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inpRenewalSubModalDefaultPrice">@lang('fields.price')</label>
                        <input type="text" id="inpRenewalSubModalDefaultPrice" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inpRenewalSubModalPrice">@lang('fields.new_price')</label>
                        <input type="number" name="price" id="inpRenewalSubModalPrice" class="form-control" step="0.01">
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
