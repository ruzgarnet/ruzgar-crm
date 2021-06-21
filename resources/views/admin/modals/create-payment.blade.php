<div class="modal fade" tabindex="-1" role="dialog" id="createPaymentModal">
    <form method="POST" id="createPaymentForm">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('titles.subscription.create_payment')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="@lang('titles.close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inpCreatePaymentModalSubscription">@lang('fields.subscription')</label>
                        <input type="text" id="inpCreatePaymentModalSubscription" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inpCreatePaymentModalPrice">@lang('fields.price')</label>
                        <input type="number" name="price" id="inpCreatePaymentModalPrice" class="form-control" step="0.01">
                    </div>

                    <div class="form-group">
                        <label for="inpCreatePaymentModalDate">@lang('fields.date')</label>
                        <input type="date" name="date" id="inpCreatePaymentModalDate" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="txtCreatePaymentDescription">@lang('fields.description')</label>
                        <textarea name="description" id="txtCreatePaymentDescription" class="form-control" rows="3"></textarea>
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
