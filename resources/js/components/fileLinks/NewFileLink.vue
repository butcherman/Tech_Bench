<template>
    <div>
        <b-form @submit="validateForm" :validated="validated" ref="newLinkForm" novalidate>
            <fieldset :disabled="submitted">
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
                        <b-form-group
                            id="expire"
                            label="Expires On:"
                            label-for="link_expire"
                        >
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
                        <b-form-checkbox switch v-model="hasInstructions">Add Instructions</b-form-checkbox>
                    </div>
                </div>
                <div class="row justify-content-center mt-2">
                    <div class="col">
                        <transition name="fade">
                            <div id="instructionsBlock" v-if="hasInstructions">
                                <editor v-if="hasInstructions" :init="{plugins: 'autolink', height:500}" v-model=form.instructions></editor>
                            </div>
                        </transition>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <file-upload ref="fileUpload"
                            :submit_url="route('links.data.store')"
                            @uploadFinished="uploadFinished">
                        </file-upload>
                    </div>
                </div>
            </fieldset>
            <form-submit
                :button_text="buttonText"
                :submitted="submitted"
            ></form-submit>
        </b-form>
        <customer-search :show_form="showSearch" @selectedCust="updateCust" @selectCanceled="selectCancel"></customer-search>
    </div>
</template>

<script>
    export default {
        props: [
            'expire_date',
        ],
        data: function () {
            return {
                validated: false,
                hasInstructions: false,
                showSearch: false,
                custLinkMsg: null,
                submitted: false,
                buttonText: 'Create New Link',
                form: {
                    name: '',
                    expire: this.expire_date,
                    allowUp: true,
                    link: false,
                    instructions: '',
                    customerID: '',
                    customerName: '',
                }
            }
        },
        methods: {
            //  Validate all information is correct
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs.newLinkForm.checkValidity() === false)
                {
                    this.validated = true;
                }
                else
                {
                    var fileZone = this.$refs.fileUpload;
                    this.submitted = true;
                    if(fileZone.getFileCount() > 0)
                    {
                        fileZone.submitFiles(this.form);
                    }
                    else
                    {
                        this.createLink();
                    }
                }
            },
            createLink()
            {
                this.buttonText = 'Processing...';

                //  Wipe any instructions if the instructions switch was turned off
                if(!this.hasInstructions)
                {
                    this.form.instructions = '';
                }

                axios.post(this.route('links.data.store'), this.form)
                    .then(res => {
                        var url = this.route('links.details', [res.data.link, res.data.name]);
                        window.location.href = url;
                    }).catch(error => this.$bvModal.msgBoxOk('Create new link operation failed.  Please try again later.'));
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
            //  After files have uploaded, create a new link to attach files to
            uploadFinished()
            {
                this.createLink();
            }
        },
    }
</script>
