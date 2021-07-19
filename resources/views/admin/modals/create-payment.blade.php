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

                    <div class="form-group">
                        <label for="slcStatus">@lang('fields.status')</label>
                        <select name="status" id="slcStatus" class="custom-select">
                            <option title="Durum" selected disabled>Seçiniz</option>
                            @foreach ($statuses as $index => $status)
                                <option value="{{ $index }}" title="{{ $status }}">
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="chkLumpSum">@lang('fields.payment_type')</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="chkLumpSum"
                                        name="is_lump_sum" value="1">
                                    <label class="custom-control-label"
                                        for="chkLumpSum">@lang('fields.lump_sum')</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="inpLumpSumValue">@lang('fields.month')</label>
                                <input type="number" name="lump_sum_value" id="inpLumpSumValue" class="form-control">
                            </div>
                        </div>
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
