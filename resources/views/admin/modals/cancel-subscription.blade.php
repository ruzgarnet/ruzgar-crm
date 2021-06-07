<form method="POST" id="cancelSubscriptionForm">
    @method('put')
    <div class="modal fade" tabindex="-1" role="dialog" id="cancelSubscriptionModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('titles.cancel_subscription')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inpCancelSubscriptionModalCustomer">@lang('fields.customer')</label>
                        <input type="text" id="inpCancelSubscriptionModalCustomer" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inpCancelSubscriptionModalService">@lang('fields.service')</label>
                        <input type="text" id="inpCancelSubscriptionModalService" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="txtCancelSubscriptionModalDescription">@lang('fields.description')</label>
                        <textarea name="description" id="txtCancelSubscriptionModalDescription" class="form-control" rows="3"></textarea>
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
