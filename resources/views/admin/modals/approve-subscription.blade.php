<div class="modal approve-modal fade" tabindex="-1" role="dialog" id="approveSubscriptionModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('titles.actions.approve.subscription')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @lang('warnings.approve.subscription')
            </div>
            <div class="modal-footer">
                <form method="POST">
                    @method('put')
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> @lang('titles.approve')</button>
                </form>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> @lang('titles.cancel')</button>
            </div>
        </div>
    </div>
</div>
