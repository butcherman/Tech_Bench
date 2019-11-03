<template>
<div>
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">Link Details</div>
                <div class="card-body">
                    <dl class="row h-100"  v-if="loadDone">
                        <dt class="col-3 text-right">Link Name:</dt>
                        <dd class="col-9 text-left">{{details.link_name}}</dd>
                        <dt class="col-3 text-right">Customer:</dt>
                        <dd class="col-9 text-left">{{details.cust_name}}</dd>
                        <dt class="col-3 text-right">Expire Date:</dt>
                        <dd class="col-9 text-left">{{details.exp_format}}</dd>
                        <dt class="col-3 text-right">Allow Upload:</dt>
                        <dd class="col-9 text-left">{{details.allow_upload}}</dd>
                        <dt class="col-3 text-right">Link:</dt>
                        <dd class="col-9 text-left"><a :href="route('file-links.show', hash)" target="_blank">{{route('file-links.show', hash)}}</a></dd>
                    </dl>
                    <div v-else-if="error">
                        <h5 class="text-center">Problem Loading Data...</h5>
                    </div>
                    <img v-else src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">Actions</div>
                <div class="card-body">
                    <b-button block variant="primary" v-b-modal.link-edit-modal>Edit Link</b-button>
                    <a :href="'mailto:?subject=A File Link Has Been Created For You&body=View the link details here: '+route('file-links.show', hash)"  class="btn btn-block btn-primary">Email Link</a>
                    <b-button block variant="primary" @click="confirmDelete">Delete Link</b-button>
                </div>
            </div>
        </div>
    </div>
    <b-modal id="link-edit-modal" title="Edit Link Details" ref="editLinkModal" hide-footer centered size="lg" @shown="openEditForm">
        <b-form @submit="validateForm" ref="editLinkForm" novalidate :validated="validated">
            <input type="hidden" name="_token" :value=token />
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
            <b-form-group id="expire"
                        label="Expires On:"
                        label-for="link_expire">
                <b-form-input id="link_expire"
                            type="date"
                            name="expire"
                            required
                            v-model="form.expire">
                </b-form-input>
                <b-form-invalid-feedback>Please Enter An Expiration Date For This Link</b-form-invalid-feedback>
            </b-form-group>
            <div class="row justify-content-center mt-4">
                <div class="col-6 col-md-2 order-2 order-md-1">
                    <div class="onoffswitch">
                        <input type="checkbox" name="allowUp" class="onoffswitch-checkbox" id="allowUp" v-model="form.allowUpload">
                        <label class="onoffswitch-label" for="allowUp">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
                <div class="col-md-4 align-self-center order-1 order-md-2">
                    <h5 class="text-center">Allow User to Upload Files</h5>
                </div>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-6 col-md-2 order-2 order-md-1">
                    <div class="onoffswitch">
                        <input type="checkbox" name="linkCustomer" class="onoffswitch-checkbox" id="linkCustomer" @change="attachCustomer" v-model="form.selectedCustomer">
                        <label class="onoffswitch-label" for="linkCustomer">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
                <div class="col-md-4 align-self-center order-1 order-md-2">
                    <h5 class="text-center">{{button.customerLink}} <span id="explain-allow" class="ti-help-alt"></span></h5>
                    <b-popover :target="'explain-allow'" trigger="hover" placement="right">
                        <div class="text-center">By allowing this option, you will be able to quickly move an uploaded file to the customer's saved files.</div>
                    </b-popover>
                </div>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-6 col-md-2 order-2 order-md-1">
                    <div class="onoffswitch">
                        <input type="checkbox" name="addInstructions" class="onoffswitch-checkbox" id="addInstructions" v-model="form.hasInstructions">
                        <label class="onoffswitch-label" for="addInstructions">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
                <div class="col-md-4 align-self-center order-1 order-md-2">
                    <h5 class="text-center">Add Instructions</h5>
                </div>
            </div>
            <transition name="fade">
                <div id="instructionsBlock" v-if="form.hasInstructions">
                    <editor v-if="form.hasInstructions" :init="{plugins: 'autolink', height:500}" v-model=form.instructions></editor>
                </div>
            </transition>
            <input type="hidden" name="customerID" v-model="form.customerTag">
            <b-button type="submit" block variant="primary" class="mt-4" :disabled="button.disable">{{button.text}}</b-button>
        </b-form>
        <b-modal id="select-customer" title="Search For Customer" ref="selectCustomerModal" scrollable @cancel="cancelSelectCustomer">
            <b-form @submit="searchCustomer">
                <b-input-group>
                    <b-form-input type="text" v-model="searchField"></b-form-input>
                    <b-input-group-append>
                        <b-button varient="outline-secondary" @click="searchCustomer"><span class="ti-search"></span></b-button>
                    </b-input-group-append>
                </b-input-group>
            </b-form>
            <div id="search-results" class="mt-4" v-if="searchResults.length > 0">
                <h4 class="text-center">Select A Customer</h4>
                <b-list-group>
                    <b-list-group-item v-for="res in searchResults" v-bind:key="res.cust_id" class="pointer" @click="selectCustomer(res)">{{res.name}}</b-list-group-item>
                </b-list-group>
            </div>
        </b-modal>
    </b-modal>
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">Link Instructions</div>
                <div class="card-body">
                    <span v-if="details.note === null && loadDone"><h5 class="text-center">No Instructions</h5></span>
                    <img v-else-if="!loadDone" src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
                    <div v-else v-html="details.note"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props: [
            'link_id'
        ],
        data() {
            return {
                token: window.techBench.csrfToken,
                loadDone: false,
                error: false,
                validated: false,
                details: [],
                hash: '',
                form: {
                    name: '',
                    expire: '',
                    selectedCustomer: '',
                    customerTag: '',
                    allowUpload: '',
                    hasInstructions: false,
                    instructions: '',
                },
                button: {
                    disable: false,
                    text: 'Update Link',
                    customerLink: 'Link To Customer'
                },
                searchField: '',
                searchResults: [],
            }
        },
        created()
        {
            this.getDetails();
        },
        methods:
        {
            getDetails()
            {
                axios.get(this.route('links.data.show', this.link_id))
                    .then(res => {
                        this.details = res.data;
                        this.hash = res.data.link_hash;
                        this.form.name = res.data.link_name;
                        this.form.expire = res.data.exp_stamp;
                        this.form.allowUpload = res.data.allow_upload === 'Yes' ? true : false;
                        this.searchField = res.data.cust_id > 0 ? res.data.cust_name : '';
                        this.form.selectedCustomer = res.data.cust_id > 0 ? true : false;
                        this.form.instructions = res.data.note;
                        this.button.customerLink = res.data.cust_id > 0 ? res.data.cust_name : 'Link To Customer';
                        this.loadDone = true;
                    }).catch(error => { this.error = true; });
            },
            openEditForm()
            {
                this.form.hasInstructions = this.form.instructions != null ? true : false;
            },
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs.editLinkForm.checkValidity() === false)
                {
                    this.validated = true;
                }
                else
                {
                    this.submitForm();
                }
            },
            submitForm()
            {
                this.button.text = 'Loading...';
                this.button.disable = true;

                axios.put(this.route('links.data.update', this.link_id), this.form)
                    .then(res => {
                        this.getDetails();
                        this.$refs['editLinkModal'].hide();
                        this.button.text = 'Update Link';
                        this.button.disable = false;
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            attachCustomer()
            {
                if(this.form.selectedCustomer)
                {
                    this.$refs.selectCustomerModal.show();
                }
                else
                {
                    this.form.customerTag = '';
                    this.button.customerLink = 'Link To Customer';
                    this.searchField = '';
                }
                console.log(this.form.selectedCustomer);
            },
            searchCustomer(e)
            {
                e.preventDefault();
                axios.post(this.route('customer.search'), {search:this.searchField})
                    .then(res => {
                        this.searchResults = res.data;
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            selectCustomer(custData)
            {
                this.searchField = custData.name;
                this.form.customerTag = custData.cust_id;
                this.button.customerLink = 'Linked to '+custData.name;
                this.searchResults = [];
                console.log(this.form.customerTag);
            },
            cancelSelectCustomer() {
                this.searchField = '';
                this.form.customerTag = '';
                this.button.customerLink = 'Link to Customer';
                this.searchResults = [];
                this.form.selectedCustomer = false;
            },
            confirmDelete()
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
                    axios.delete(this.route('links.data.destroy', [this.link_id]))
                    .then(res => {
                        if(res.data.success)
                        {
                            window.location.href = this.route('links.index');
                        }
                        else
                        {
                            alert('We are having difficulties processing your request\nPlease try again later.');
                        }
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                });
            }
        }
    }
</script>
