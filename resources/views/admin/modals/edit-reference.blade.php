<form method="POST" id="editReferenceForm">
    @method('put')
    <div class="modal fade" tabindex="-1" role="dialog" id="editReferenceModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('titles.edit_payment')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="@lang('titles.close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inpEditReferenceModalReference">@lang('fields.reference')</label>
                        <input type="text" id="inpEditReferenceModalReference" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inpEditReferenceModalReferenced">@lang('fields.referenced')</label>
                        <input type="text" id="inpEditReferenceModalReferenced" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="slcEditReferenceModalStatus">@lang('fields.status')</label>
                        <select name="status" id="slcEditReferenceModalStatus" class="custom-select">
                            @foreach ($referenceStatus as $key)
                                <option value="{{ $key }}" title="@lang("tables.reference.status.descriptions.{$key}")">
                                    @lang("tables.reference.status.titles.{$key}")
                                </option>
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
