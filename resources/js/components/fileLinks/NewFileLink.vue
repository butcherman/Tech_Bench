<template>
    <div>
        <b-form @submit="validateForm" :action="route('links.data.store')" method="post" enctype="multipart/form-data" ref="newLinkForm" novalidate :validated="validated">
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
                        <input type="checkbox" name="allowUp" class="onoffswitch-checkbox" id="allowUp" checked>
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
            <vue-dropzone id="dropzone"
                        class="filedrag"
                        ref="fileDropzone"
                        @vdropzone-total-upload-progress="updateProgressBar"
                        @vdropzone-sending="sendingFiles"
                        @vdropzone-queue-complete="queueComplete"
                        :options="dropzoneOptions">
            </vue-dropzone>
            <b-progress v-show="showProgress" :value="progress" variant="success" striped animate show-progress></b-progress>
            <b-button type="submit" block variant="primary" :disabled="button.disable">{{button.text}}</b-button>
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
    </div>
</template>

<script>
    export default {
        props: [
            'expire_date',
        ],
        data: function () {
            return {
                token: window.techBench.csrfToken,
                validated: false,
                showProgress: false,
                progress: 0,
                form: {
                    name: '',
                    expire: this.expire_date,
                    selectedCustomer: false,
                    customerTag: '',
                    hasInstructions: false,
                    instructions: '',
                },
                button: {
                    disable: false,
                    text: 'Submit',
                    customerLink: 'Link To Customer'
                },
                dropzoneOptions: {
                    url: this.route('links.data.store'),
                    autoProcessQueue: false,
                    parallelUploads: 1,
                    maxFiles: 5,
                    maxFilesize: window.techBench.maxUpload,
                    addRemoveLinks: true,
                    chunking: true,
                    chunkSize: 5000000,
                    parallelChunkUploads: false,
                },
                searchField: '',
                searchResults: [],
            }
        },
        methods: {
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs.newLinkForm.checkValidity() === false)
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
                var myDrop = this.$refs.fileDropzone;

                this.button.text = 'Loading...';
                this.button.disable = true;

                if(myDrop.getQueuedFiles().length > 0)
                {
                    this.showProgress = true;
                    myDrop.processQueue();
                }
                else
                {
                    this.createLink();
                }
            },
            createLink()
            {
                    var linkForm = new FormData(document.querySelector('form'));
                    linkForm.append('_completed', true);
                    linkForm.append('note', this.form.hasInstructions ? this.form.instructions : '');
                    axios.post(this.route('links.data.store'), linkForm)
                        .then(res => {
                            console.log(res);
                            var url = this.route('links.details', [res.data.link, res.data.name]);
                            window.location.href = url;
                        }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            updateProgressBar(progress)
            {
                this.progress = progress;
            },
            sendingFiles(file, xhr, formData)
            {
                formData.append('_token', this.token);
                formData.append('name', this.name);
                formData.append('expire', this.expire);
                formData.append('customer_tag', this.customerTag);
            },
            queueComplete()
            {
                this.button.text = 'Processing, please wait...';
                this.createLink();
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
                }
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
            },
            cancelSelectCustomer() {
                this.searchField = '';
                this.form.customerTag = '';
                this.button.customerLink = 'Link to Customer';
                this.searchResults = [];
                this.form.selectedCustomer = false;
            }
        },
        mounted()
        {
            this.$root.$on('bv::modal::hide', (bvEvent, modalID) => {
                if(this.searchField == '')
                {
                    this.form.selectedCustomer = false;
                }
                else
                {
                    this.searchField = '';
                }
            });
        }
    }
</script>
