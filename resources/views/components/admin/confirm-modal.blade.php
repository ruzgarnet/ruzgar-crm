<form method="POST" id="{{ $id }}Form">
    @method($method ?? 'post')
    <div class="modal fade" tabindex="-1" role="dialog" id="{{ $id }}Modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="@lang('titles.close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ $message }}
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-{{ $buttonType ?? 'primary' }}">{{ $buttonText }}</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('titles.cancel')</button>
                </div>
            </div>
        </div>
    </div>
</form>
