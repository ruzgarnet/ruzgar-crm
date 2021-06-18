<div class="modal fade" tabindex="-1" role="dialog" id="createPaymentModal">
    <form method="POST" id="createPaymentForm">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('titles.subscription.create_payment')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inpCreatePaymenttModalFullName">@lang('fields.name')</label>
                        <input type="text" value="{{ $subscription->customer->full_name }}" id="inpCreatePaymenttModalFullName" class="form-control" step="0.01" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inpCreatePaymenttModalPrice">@lang('fields.price')</label>
                        <input type="number" name="price" id="inpCreatePaymenttModalPrice" class="form-control" step="0.01">
                    </div>

                    <div class="form-group">
                        <label for="inpCreatePaymenttModalDate">@lang('fields.date')</label>
                        <input type="date" name="date" id="inpCreatePaymenttModalDate" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="slcStatus">@lang('fields.status')</label>
                        <select name="status" id="slcStatus" class="custom-select selectpicker">
                            @foreach ($statuses as $key => $status)
                                <option value="{{ $key }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="slcType">@lang('fields.type')</label>
                        <select name="type" id="slcType" class="custom-select selectpicker">
                            @foreach ($types as $key => $type)
                                <option value="{{ $key }}">{{ $type }}</option>
                            @endforeach
                        </select>
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
