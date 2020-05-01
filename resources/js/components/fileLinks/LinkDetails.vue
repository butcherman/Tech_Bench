<template>
    <b-overlay :show="showOverlay">
        <template v-slot:overlay>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Processing...</h4>
        </template>
        <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">Details:</div>
                    <div class="card-body">
                        <div v-if="error">
                            <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to load Details...</h5>
                        </div>
                        <atom-spinner v-else-if="loading"
                            :animation-duration="1000"
                            :size="60"
                            color="#ff1d5e"
                            class="mx-auto"
                        />
                        <dl class="row h-100"  v-else>
                            <dt class="col-md-3 text-md-right">Link Name:</dt>
                            <dd class="col-md-9 text-md-left pt-2 pt-md-0">{{details.link_name}}</dd>
                            <dt class="col-md-3 text-md-right pt-3 pt-md-0">Customer:</dt>
                            <dd class="col-md-9 text-md-left pt-2 pt-md-0">{{details.cust_name}}</dd>
                            <dt class="col-md-3 text-md-right pt-3 pt-md-0">Expire Date:</dt>
                            <dd class="col-md-9 text-md-left pt-2 pt-md-0">
                                <span :class="details.expired ? 'text-danger' : null">{{details.exp_format}}</span>
                            </dd>
                            <dt class="col-md-3 text-md-right pt-3 pt-md-0">Allow Upload:</dt>
                            <dd class="col-md-9 text-md-left pt-2 pt-md-0">{{details.allow_upload}}</dd>
                            <dt class="col-md-3 text-md-right pt-3 pt-md-0">Link URL:</dt>
                            <dd class="col-md-9 text-md-left pt-2 pt-md-0">
                                <a :href="route('file-links.show', details.link_hash)" target="_blank">{{route('file-links.show', details.link_hash)}}</a>
                                <b-button
                                    pill
                                    :variant="copyClass"
                                    size="sm"
                                    class="d-block d-md-inline-block"
                                    title="Copy Link to Clipboard"
                                    v-clipboard:copy="route('file-links.show', details.link_hash)"
                                    v-clipboard:success="onCopySuccess"
                                    v-clipboard:error="onCopyError"
                                    v-b-tooltip.hover>
                                    <i class="fas fa-copy"></i>
                                </b-button>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">Actions:</div>
                    <div class="card-body">
                        <b-button pill block variant="primary" v-b-modal.link-edit-modal>
                            <i class="far fa-edit"></i>
                            Edit Link
                        </b-button>
                        <b-button pill block variant="primary"
                            :href="'mailto:?subject=A File Link Has Been Created For You&body=View the link details here: '+route('file-links.show', details.link_hash)"
                        >
                            <i class="far fa-envelope"></i>
                            Email Link
                        </b-button>
                        <b-button pill block variant="primary" @click="deleteLink">
                            <i class="far fa-trash-alt"></i>
                            Delete Link
                        </b-button>
                        <link-details-form :details="details" @updateSuccessful="getDetails"></link-details-form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <link-instructions :link_id="init_details.link_id" :instructions="details.note"></link-instructions>
            </div>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <link-files :link_id="init_details.link_id" :cust_id="details.cust_id"></link-files>
            </div>
        </div>
    </b-overlay>
</template>

<script>
    export default {
        props: {
            init_details: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                showOverlay: false,
                error:       false,
                loading:     false,
                copyClass:  'outline-secondary',
                details:     this.init_details,
            }
        },
        methods: {
            //  Pull the details about the link
            getDetails()
            {
                this.loading = true;
                axios.get(this.route('links.data.show', this.details.link_id))
                    .then(res => {
                        this.loading = false;
                        this.details = res.data;
                    }).catch(error => { this.error = true; });
            },
            //  Delete the link and all of its attached files
            deleteLink()
            {
                this.$bvModal.msgBoxConfirm('Are you sure?  This cannot be undone.', {
                    title: 'Please Confirm',
                    size: 'sm',
                    buttonSize: 'sm',
                    okVariant: 'danger',
                    okTitle: 'YES',
                    cancelTitle: 'NO',
                    footerClass: 'p-2',
                    hideHeaderClose: false,
                    centered: true
                })
                .then(value => {
                    this.showOverlay = true;
                    axios.delete(this.route('links.data.destroy', [this.details.link_id]))
                    .then(res => {
                        if(res.data.success)
                        {
                            window.location.href = this.route('links.index');
                        }
                        else
                        {
                            this.showOverlay = false;
                            this.$bvModal.msgBoxOk('Delete link operation failed.  Please try again later.');
                        }
                    }).catch(error => this.$bvModal.msgBoxOk('Something bad happened.  Please try again later.'));
                });
            },
            //  Successful and Error functions for copy link URL to clipboard
            onCopySuccess()
            {
                this.copyClass = 'success';
            },
            onCopyError()
            {
                this.copyClass = 'danger';
            },
        }
    }
</script>
