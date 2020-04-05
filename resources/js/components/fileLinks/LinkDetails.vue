<template>
    <div>
        <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">Details:</div>
                    <div class="card-body">
                        <div v-if="error">
                            <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to load Details...</h5>
                        </div>
                        <img v-else-if="!loadDone" src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
                        <dl class="row h-100"  v-else>
                            <dt class="col-md-3 text-md-right">Link Name:</dt>
                            <dd class="col-md-9 text-md-left pt-2 pt-md-0">{{details.link_name}}</dd>
                            <dt class="col-md-3 text-md-right pt-3 pt-md-0">Customer:</dt>
                            <dd class="col-md-9 text-md-left pt-2 pt-md-0">{{details.cust_name}}</dd>
                            <dt class="col-md-3 text-md-right pt-3 pt-md-0">Expire Date:</dt>
                            <dd class="col-md-9 text-md-left pt-2 pt-md-0">{{details.exp_format}}</dd>
                            <dt class="col-md-3 text-md-right pt-3 pt-md-0">Allow Upload:</dt>
                            <dd class="col-md-9 text-md-left pt-2 pt-md-0">{{details.allow_upload}}</dd>
                            <dt class="col-md-3 text-md-right pt-3 pt-md-0">Link URL:</dt>
                            <dd class="col-md-9 text-md-left pt-2 pt-md-0">
                                <a :href="linkURL" target="_blank">{{linkURL}}</a>
                                <b-button
                                    pill
                                    :variant="copyClass"
                                    size="sm"
                                    class="d-block d-md-inline-block"
                                    title="Copy Link to Clipboard"
                                    v-clipboard:copy="linkURL"
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
                        <b-button pill block variant="primary" v-b-modal.link-edit-modal @click="resetEditForm">Edit Link</b-button>
                        <b-button pill block variant="primary" :href="'mailto:?subject=A File Link Has Been Created For You&body=View the link details here: '+linkURL">Email Link</b-button>
                        <b-button pill block variant="primary" @click="confirmDelete">Delete Link</b-button>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="link-edit-modal" title="Edit Link Details" ref="editLinkModal" hide-footer centered size="lg">
            <b-form @submit="validateEditForm" ref="editLinkForm" novalidate :validated="validated">
                <div class="row">
                    <div class="col-md-6">
                        <b-form-group id="name"
                                    label="Link Name:"
                                    label-for="link_name">
                            <b-form-input id="link_name"
                                        type="text"
                                        name="name"
                                        placeholder="Enter A User Friendly Name For This Link"
                                        required
                                        v-model="form.name">
                            </b-form-input>
                            <b-form-invalid-feedback>Please Enter A Name For This Link</b-form-invalid-feedback>
                        </b-form-group>
                    </div>
                    <div class="col-md-6">
                        <b-form-group id="expire"
                                    label="Expires On:"
                                    label-for="link_expire">
                            <b-form-datepicker id="link_expire"
                                        required
                                        v-model="form.expire">
                            </b-form-datepicker>
                            <b-form-invalid-feedback>Please Enter An Expiration Date For This Link</b-form-invalid-feedback>
                        </b-form-group>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <b-form-checkbox v-model="form.allowUp" switch>Allow Visitor to Upload Files</b-form-checkbox>
                        <b-form-checkbox switch @change="linkCustomer" v-model="form.link">
                            Link to Customer
                            <div v-if="custLinkMsg" class="text-muted">&#123; {{custLinkMsg}} &#125;</div>
                        </b-form-checkbox>
                    </div>
                </div>
                <form-submit
                    class="mt-3"
                    :button_text="buttonText"
                    :submitted="submitted"
                ></form-submit>
            </b-form>
            <customer-search :show_form="showSearch" @selectedCust="updateCust" @selectCanceled="selectCancel"></customer-search>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: [
            'link_id'
        ],
        data() {
            return {
                loadDone: false,
                error: false,
                linkURL: '',
                copyClass: 'outline-secondary',
                details: {
                    hash: '',
                },
                form: {
                    name: '',
                    expire: '',
                    allowUp: true,
                    link: false,
                    instructions: '',
                    customerID: '',
                    customerName: '',
                },
                custLinkMsg: null,
                showSearch: false,
                validated: false,
                buttonText: 'Update Link Details',
                submitted: false,
            }
        },
        created()
        {
            this.getDetails();
        },
        methods:
        {
            //  Pull the details about the link
            getDetails()
            {
                axios.get(this.route('links.data.show', this.link_id))
                    .then(res => {
                        this.loadDone = true;
                        this.details = res.data;
                        this.linkURL = this.route('file-links.show', this.details.link_hash);
                    }).catch(error => { this.error = true; });
            },
            //  Populate the Edit Details Form
            resetEditForm()
            {
                this.form.name = this.details.link_name;
                this.form.expire = this.details.exp_stamp;
                this.form.allowUp = this.details.allow_upload === 'Yes' ? true : false;
                if(this.details.cust_id != null)
                {
                    this.form.link = true;
                    this.form.customerName = this.details.cust_name;
                    this.form.customerID = this.details.cust_id;
                    this.custLinkMsg = this.details.cust_name != null ? 'Linked to '+this.details.cust_name : '';
                }
            },
            //  Validate the Edit Details Form
            validateEditForm(e)
            {
                e.preventDefault();

                if(this.$refs.editLinkForm.checkValidity() === false)
                {
                    this.validated = true;
                }
                else
                {
                    this.submitted = true;
                    axios.put(this.route('links.data.update', this.link_id), this.form)
                    .then(res => {
                        this.getDetails();
                        this.$refs['editLinkModal'].hide();
                        this.submitted = false;
                    }).catch(error =>
                        this.$bvModal.msgBoxOk('Update link operation failed.  Please try again later.')
                    );

                }
            },
            //  Verify and delete the link
            confirmDelete()
            {
                console.log('confirm');
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
                    axios.delete(this.route('links.data.destroy', [this.link_id]))
                    .then(res => {
                        if(res.data.success)
                        {
                            window.location.href = this.route('links.index');
                        }
                        else
                        {
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
            //  Show form or cancel link to a customer ID
            linkCustomer(state)
            {
                if(state)
                {
                    this.showSearch = true;
                }
                else
                {
                    //  If turned off, clear customer information
                    this.showSearch = false;
                    this.form.customerID = '';
                    this.form.customerName = '';
                    this.custLinkMsg = null;
                }
            },
            //  Update the customer ID and Name
            updateCust(cust)
            {
                this.showSearch = false;
                this.form.customerID = cust.cust_id;
                this.form.customerName = cust.name;
                this.custLinkMsg = 'Linking to '+cust.name;
                this.form.link = true;
            },
            //  Select customer modal canceled
            selectCancel()
            {
                if(this.form.customerID == '')
                {
                    this.form.link = false;
                    this.custLinkMsg = null;
                }
                this.showSearch = false;
            },
        }
    }
</script>
