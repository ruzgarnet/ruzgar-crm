<div class="modal fade" tabindex="-1" role="dialog" id="deleteModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('titles.actions.delete')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @lang('warnings.delete')
            </div>
            <div class="modal-footer">
                <form method="POST" id="deleteForm">
                    @method('delete')
                    <button type="submit" class="btn btn-danger">@lang('titles.delete')</button>
                </form>
                <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('titles.cancel')</button>
            </div>
        </div>
    </div>
</div>
