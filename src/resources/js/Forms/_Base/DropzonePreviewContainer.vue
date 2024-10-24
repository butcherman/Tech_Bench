<template>
    <div id="preview-template-container" class="d-none">
        <div class="dz-preview-wrapper">
            <div class="dz-preview">
                <div class="dz-preview dz-file-preview">
                    <div class="dz-image h-100">
                        <img data-dz-thumbnail />
                    </div>
                    <div class="dz-details">
                        <div class="dz-size" data-dz-size></div>
                        <div class="dz-filename">
                            <span data-dz-name />
                        </div>
                    </div>
                    <span class="dz-remove dz-tooltip" data-dz-remove>
                        <fa-icon icon="fa-trash-alt" />
                        <span class="dz-tooltip-text">Remove File</span>
                    </span>
                    <div class="dz-progress">
                        <span class="dz-upload" data-dz-uploadprogress />
                    </div>
                    <div class="dz-success-mark">
                        <fa-icon icon="fa-check" />
                    </div>
                    <div class="dz-error-mark">
                        <fa-icon icon="fa-exclamation-circle" />
                    </div>
                    <div class="dz-error-message">
                        <span data-dz-errormessage />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
