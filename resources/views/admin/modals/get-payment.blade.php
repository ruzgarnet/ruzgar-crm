<form method="POST" id="paymentForm">
    <div class="modal fade" tabindex="-1" role="dialog" id="paymentModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('titles.get_payment')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="@lang('titles.close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="slcType">@lang('fields.payment_type')</label>
                        <select name="type" id="slcType" class="custom-select">
                            <option value="0" selected disabled>@lang("tables.payment.select_type")</option>
                            @foreach ($paymentTypes as $type)
                                <option value="{{ $type }}">@lang("tables.payment.types.{$type}")</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="inpPrice">@lang('fields.price')</label>
                        <input type="text" id="inpPrice" class="form-control" readonly>
                    </div>

                    <div class="payment-types payment-type-4 payment-type-5">
                        <div class="form-group">
                            <label for="inpFullName">@lang('fields.card.name_surname')</label>
                            <input type="text" name="card[full_name]" id="inpFullName" class="form-control" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="inpCardNumber">@lang('fields.card.number')</label>
                            <input type="text" name="card[number]" id="inpCardNumber"
                                class="form-control credit-card-mask" autocomplete="off">
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpCardExpireDate">@lang('fields.card.expire_date')</label>
                                    <input type="text" name="card[expire_date]" id="inpCardExpireDate"
                                        class="form-control expire-date-mask" placeholder="AA/YY" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpCCVCode">@lang('fields.card.security_code')</label>
                                    <input type="text" name="card[security_code]" id="inpCCVCode" class="form-control" autocomplete="off">
                                    <div class="payment-types payment-type-5">
                                        <small>Sadece provizyon için doldurunuz.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="payment-types payment-type-4">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="auto_payment" class="custom-control-input" id="chkAutoPayment" checked>
                                    <label class="custom-control-label" for="chkAutoPayment">Otomatik ödeme tanımla.</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right mb-0 payment-types payment-type-5">
                            <button id="btnPaymentPreAuth" type="button" class="btn btn-warning">@lang('titles.payment_pre_auth')</button>
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

@push('script')
    <script src="/assets/admin/vendor/cleave/cleave.min.js"></script>
@endpush
