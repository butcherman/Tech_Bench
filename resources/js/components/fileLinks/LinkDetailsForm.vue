<template>
    <b-modal id="link-edit-modal" title="Edit Link Details" ref="editLinkModal" hide-footer centered size="lg">
        <b-form @submit="validateForm" ref="editLinkForm" novalidate :validated="validated">
            <fieldset :disabled="submitted">
                <div class="row">
                    <div class="col-md-6">
                        <b-form-group id="name"
                            label="Link Name:"
                            label-for="link_name"
                        >
                            <b-form-input id="link_name"
                                type="text"
                                name="name"
                                placeholder="Enter A User Friendly Name For This Link"
                                required
                                v-model="form.name"
                            ></b-form-input>
                            <b-form-invalid-feedback>Please Enter A Name For This Link</b-form-invalid-feedback>
                        </b-form-group>
                    </div>
                    <div class="col-md-6">
                        <b-form-group id="expire"
                            label="Expires On:"
                            label-for="link_expire"
                        >
                            <b-form-datepicker id="link_expire"
                                required
                                v-model="form.expire"
                            ></b-form-datepicker>
                            <b-form-invalid-feedback>Please Enter An Expiration Date For This Link</b-form-invalid-feedback>
                        </b-form-group>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <b-form-checkbox v-model="form.allow_upload" switch>Allow Visitor to Upload Files</b-form-checkbox>
                        <b-form-checkbox switch @change="linkCustomer" v-model="form.link">
                            Link to Customer
                            <div v-if="custLinkMsg" class="text-muted">&#123; {{custLinkMsg}} &#125;</div>
                        </b-form-checkbox>
                        <customer-search :show_form="showSearch" @selectedCust="updateCust" @selectCanceled="selectCancel"></customer-search>
                    </div>
                </div>
            </fieldset>
            <form-submit
                class="mt-3"
                button_text="Update Link Details"
                :submitted="submitted"
            ></form-submit>
        </b-form>
    </b-modal>
</template>

<script>
    export default {
        props: {
            details: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                submitted:   false,
                validated:   false,
                custLinkMsg: this.details.cust_name ? 'Linked to '+this.details.cust_name : null,
                showSearch: false,
                form: {
                    name:         this.details.link_name,
                    expire:       this.details.exp_stamp,
                    allow_upload: this.details.allow_upload === "Yes" ? true : false,
                    link:         this.details.cust_id ? true : false,
                    cust_id:      this.details.cust_id,
                    cust_name:    this.details.cust_name,
                }
            }
        },
        methods: {
            validateForm(e)
            {
                e.preventDefault();

                if(this.$refs.editLinkForm.checkValidity() === false)
                {
                    this.validated = true;
                }
                else
                {
                    this.submitted = true;
                    axios.put(this.route('links.data.update', this.details.link_id), this.form)
                        .then(res => {
                            this.$refs['editLinkModal'].hide();
                            this.submitted = false;
                            this.$emit('updateSuccessful');
                        }).catch(error =>
                            this.$bvModal.msgBoxOk('Update link operation failed.  Please try again later.')
                        );
                }
            },
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
                    this.form.cust_id   = '';
                    this.form.cust_name = '';
                    this.custLinkMsg    = null;
                }
            },
            //  Update the customer ID and Name
            updateCust(cust)
            {
                this.showSearch     = false;
                this.form.cust_id   = cust.cust_id;
                this.form.cust_name = cust.name;
                this.custLinkMsg    = 'Linking to '+cust.name;
                this.form.link      = true;
            },
            //  Select customer modal canceled
            selectCancel()
            {
                this.form.link   = false;
                this.custLinkMsg = null;
                this.showSearch  = false;
            },
        }
    }
</script>
