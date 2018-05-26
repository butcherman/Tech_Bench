<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@yield('modal-header')</h5>
                <button class="close cancel-upload" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @yield('modal-body')
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary cancel-upload" type="button" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div> 
