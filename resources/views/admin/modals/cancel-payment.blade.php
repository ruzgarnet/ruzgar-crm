<form method="POST" id="cancelPaymentForm">
    @method('post')
    <div class="modal fade" tabindex="-1" role="dialog" id="cancelPaymentModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('titles.subscription.cancel_payment')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="@lang('titles.close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inpCancelPaymentModalSubscription">@lang('fields.customer')</label>
                        <input type="text" id="inpCancelPaymentModalSubscription" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inpCancelPaymentModalPayment">@lang('fields.payment')</label>
                        <input type="text" id="inpCancelPaymentModalPayment" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="txtCancelPaymentModalDescription">@lang('fields.description')</label>
                        <textarea name="description" id="txtCancelPaymentModalDescription" class="form-control" rows="3"></textarea>
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
