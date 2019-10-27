<template>
    <div>
        <b-form @submit="submitForm" :action="route('links.data.store')" method="post" enctype="multipart/form-data" ref="newLinkForm" novalidate :validated="validated">
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
            <input type="hidden" name="customer-id" v-model="form.customerTag">























            <b-button type="submit" block variant="primary" :disabled="button.disaable">{{button.text}}</b-button>
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
                form: {
                    name: '',
                    expire: this.expire_date,
                    selectedCustomer: false,
                    customerTag: '',
                },
                button: {
                    disable: false,
                    text: 'Submit',
                    customerLink: 'Link To Customer'
                },
                searchField: '',
                searchResults: [],
            }
        },
        methods: {
            submitForm(e)
            {
                e.preventDefault();
                console.log('submitted');
                if(this.$refs.newLinkForm.checkValidity() === false)
                {
                    this.validated = true;
                    console.log('not valid');
                }
                else
                {
                    console.log('ready to go');
                }
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
                this.form.customerTag = custData.custID;
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
            // this.$refs.selectCustomerModal.show();
            this.$root.$on('bv::modal::hide', (bvEvent, modalID) => {
                console.log('modal closed');
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
