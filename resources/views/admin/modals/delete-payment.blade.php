<form method="POST" id="deletePaymentForm">
    @method('delete')
    <div class="modal fade" tabindex="-1" role="dialog" id="deletePaymentModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('titles.subscription.delete_payment')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="@lang('titles.close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inpDeletePaymentModalSubscription">@lang('fields.customer')</label>
                        <input type="text" id="inpDeletePaymentModalSubscription" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inpDeletePaymentModalPayment">@lang('fields.payment')</label>
                        <input type="text" id="inpDeletePaymentModalPayment" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="txtDeletePaymentModalDescription">@lang('fields.description')</label>
                        <textarea name="description" id="txtDeletePaymentModalDescription" class="form-control" rows="3"></textarea>
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
