<template>
    <div class="row">
        <div class="col-md-8 d-flex">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fa fa-pencil pointer" id="edit-link-details" title="Edit Link Details" data-tooltip="tooltip" @click="openUpdate" v-b-tooltip.hover></i>
                        Details:
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-3 text-right">Link Name:</dt>
                        <dd class="col-9 text-left">{{name}}</dd>
                        <dt class="col-3 text-right">Customer:</dt>
                        <dd class="col-9 text-left">{{cust == null ? 'None' : cust}}</dd>
                        <dt class="col-3 text-right">Expire Date:</dt>
                        <dd class="col-9 text-left">{{expire}}</dd>
                        <dt class="col-3 text-right">Allow Uploads:</dt>
                        <dd class="col-9 text-left">{{allowUp ? 'Yes' : 'No'}}</dd>
                        <dt class="col-3 text-right">Link:</dt>
                        <dd class="col-9 text-left">
                            <a :href="url" id="link-url" target="_blank">{{url}}</a>
                            <i class="fa fa-clipboard pointer copy-btn" v-b-tooltip.hover v-clipboard:copy="url" v-clipboard:success="clipboardSuccess" v-clipboard:error="clipboardError" title="Copy URL to Clipboard" data-tooltip="tooltip"></i>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex">
            <div class="card w-100">
                <div class="card-header"><h5 class="card-title">Actions:</h5></div>
                <div class="card-body">
                    <b-button block variant="info" @click="openCustLink">Link to Customer</b-button>
                    <a :href="em_link" class="btn btn-block btn-info">Email Link</a>
                    <click-confirm class="btn-block">
                        <b-button block variant="info" @click="deleteLink">Delete Link</b-button>
                    </click-confirm>
                </div>
            </div>   
        </div>
        <b-modal id="link-edit-modal" title="Edit Link Details" ref="editLinkModal" hide-footer centered>
             <b-form id="update-link-form" @submit="updateLink" method="post" :action=update_route>
                <input type="hidden" name="_token" :value=token />
                <b-form-group 
                    id="name"
                    label="Link Name:"
                    label-for="link_name">
                    <b-form-input
                        id="link_name"
                        type="text"
                        name="name"
                        placeholder="Enter A User Friendly Name For This Link"
                        required
                        v-model=name>
                    </b-form-input>
                </b-form-group>
                <b-form-group 
                    id="expire"
                    label="Expires On:"
                    label-for="link_expire">
                    <b-form-input
                        id="link_expire"
                        type="date"
                        name="expire"
                        required
                        v-model=timeStamp>
                    </b-form-input>
                </b-form-group>
                <div class="row justify-content-center">
                    <div class="col-7">
                        <label class="switch">
                            <input type="checkbox" name="allowUp" v-model=allowUp checked>
                            <span class="slider round"></span>
                        </label>
                        Allow User to Upload Files 
                    </div>
                </div>
                <b-button type="submit" block variant="primary" :disabled="button.dis">{{button.text}}</b-button>
            </b-form>
        </b-modal>
        <b-modal id="customer-edit-modal" title="Link to Customer" ref="custLinkModal" hide-footer centered>
             <b-form id="edit-cust-form" @submit="updateCustomer" method="post" :action=update_cust_route>
                <input type="hidden" name="_token" :value=token />
                <b-form-group
                    id="tag-customer"
                    label="Link to Customer:"
                    label-for="customer-tag">
                    <div class="input-group">
                        <b-form-input 
                            id="customer-tag"
                            type="search"
                            name="customer_tag"
                            placeholder="Enter A Customer Number or Name (Optional)"
                            autocomplete="off"
                            v-model="customerTag"
                            @blur="searchCustomer"
                        ></b-form-input>
                        <span class="input-group-append" id="search-for-customer">
                            <button class="btn btn-outline-secondary border-left-0 border" id="search-for-customer-button" type="button" tabindex="-1" @click="searchCustomer">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div> 
                </b-form-group>
                <b-button type="submit" block variant="primary" :disabled="button.dis">{{button.custText}}</b-button>
            </b-form>
        </b-modal>
        <b-modal id="customer-selector-modal" title="Select A Customer" ref="customerSelectorModal">
            <ul class="list-group" v-for="cust in availableCustomers">
                <li class="list-group-item"><a href="#" :data-value="cust.cust_id+' - '+cust.name" @click="pickCustomer">{{cust.cust_id}} - {{cust.name}}</a></li>
            </ul>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: [
            'link_id',
            'index_route',
            'details_route',
            'guest_route',
            'update_route',
            'search_route',
            'update_cust_route',
            'em_link_route',
            'del_link_route',
        ],
        data() {
            return {
                name:        '',
                expire:      '',
                timeStamp:   '',
                cust:        '',
                allowUp:     '',
                url:         '',
                customerTag: '',
                cust:        '',
                em_link: this.em_link_route,
                token: window.techBench.csrfToken,
                availableCustomers: [],
                button: {
                    custText: 'Link to Customer',
                    text:     'Update Link Details',
                    dis:      false
                }
            }
        },
        created()
        {
            this.getDetails();
        },
        methods: 
        {
            //  Get the details of the file link
            getDetails()
            {
                axios.get(this.details_route)
                    .then(res => {
                        this.name      = res.data.link_name;
                        this.expire    = res.data.expire;
                        this.timeStamp = res.data.timeStamp;
                        this.allowUp   = res.data.allow_upload;
                        this.cust      = res.data.name;
                        this.url       = this.guest_route.replace(':hash', res.data.link_hash);
                        this.em_link   = this.em_link.replace(':hash', res.data.link_hash);
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
            //  Open the update details form
            openUpdate()
            {
                this.$refs.editLinkModal.show();
            },
            //  Submit the update details form
            updateLink(e)
            {
                e.preventDefault();
                
                axios.put(this.update_route, {
                        name:    this.name,
                        expire:  this.timeStamp,
                        allowUp: this.allowUp 
                    })
                    .then(res => {
                        this.$refs.editLinkModal.hide();
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
            },
            //  Copy link to clipboard - action on success
            clipboardSuccess()
            {
                alert('Link Copied to Clipboard');
            },
            //  Copy link to clipboard - action on failure
            clipboardError()
            {
                alert('Something Prevented Copying Link to the Clipboard');
            },
            //  Open the form to attach customer to link
            openCustLink()
            {
                this.$refs.custLinkModal.show();
            },
            //  Search for the customer entered in the search form
            searchCustomer()
            {
                if(this.customerTag == '')
                {
                    this.customerTag = 'NULL';
                }
                var url = this.search_route.replace(':id', this.customerTag);
                axios.get(url)
                    .then(res => {
                        this.$refs.customerSelectorModal.show();
                        this.availableCustomers = res.data;
                    })
                    .catch(error=> alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
            //  Select the customer to add to the search form
            pickCustomer(e)
            {
                e.preventDefault();
                this.customerTag = e.currentTarget.dataset.value;
                this.$refs.customerSelectorModal.hide();
            },
            //  Submot the add customer to link form
            updateCustomer(e)
            {
                e.preventDefault();
                var url = this.update_cust_route.replace(':id', this.link_id);
                axios.post(url, {
                    customer_tag: this.customerTag,
                })
                .then(res => {
                    this.getDetails();
                    this.$refs.custLinkModal.hide();
                })
                .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
            //  Delete a file link
            deleteLink()
            {
                var obj = this;
                
                var url = obj.del_link_route.replace(':linkID', obj.link_id)
                axios.delete(url)
                    .then(res => {
                        window.location.href = obj.index_route;
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            }
        }
    }
</script>
