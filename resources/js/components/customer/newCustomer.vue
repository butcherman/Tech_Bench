<template>
    <div>
        <b-form @submit="validateForm" :validated="validated" ref="newCustomerForm" novalidate>
            <div class="row">
                <div class="col-md-6">
                    <b-form-group label-for="cust_id">
                        <template slot="label">
                            Customer ID:
                            <i class="far fa-question-circle pointer" title="Click for Details" v-b-tooltip:hover v-b-modal.modal-cust-id></i>
                        </template>
                        <b-form-input
                            id="cust_id"
                            type="number"
                            v-model="form.cust_id"
                            :state="state.id"
                            :class="loading.cust_id ? 'loading' : ''"
                            @blur="checkID"
                            placeholder="Enter Customer ID Number"></b-form-input>
                            <b-form-invalid-feedback>This Customer ID Is Already Taken by {{dup.name}}.  Click <a :href="dup.url">here</a> to view customer.</b-form-invalid-feedback>
                    </b-form-group>
                </div>
                <div class="col-md-6">
                    <b-form-group  label-for="parent_id">
                        <template slot="label">
                            Parent Site ID:
                            <i class="far fa-question-circle pointer" title="Click for Details" v-b-tooltip:hover v-b-modal.modal-parent></i>
                        </template>
                        <b-input-group>
                            <b-form-input
                                id="parent_id"
                                type="text"
                                v-model="form.parent_name"
                                :state="state.parent"
                                :class="loading.parent ? 'loading' : ''"
                                @blur="checkParent"
                                placeholder="(optional)">
                            </b-form-input>
                            <b-input-group-append>
                                <b-button varient="primary" @click="checkParent"><span class="fas fa-search"></span></b-button>
                            </b-input-group-append>
                        </b-input-group>
                    </b-form-group>
                </div>
            </div>
            <b-form-group label="Customer Name:" label-for="cust-name">
                <b-form-input
                    id="cust-name"
                    type="text"
                    v-model="form.name"
                    required
                    placeholder="Enter Customer Name"></b-form-input>
                    <b-form-invalid-feedback>You must enter a customer name</b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="DBA Name:" label-for="dba-name">
                <b-form-input
                    id="dba-name"
                    v-model="form.dba_name"
                    type="text"
                    placeholder="Enter Customer DBA/AKA Name"></b-form-input>
            </b-form-group>
            <b-form-group label="Address:" label-for="cust-address">
                <b-form-input
                    id="cust-address"
                    v-model="form.address"
                    type="text"
                    required
                    placeholder="Enter Customer Address"></b-form-input>
                    <b-form-invalid-feedback>You must enter a customer address</b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="City:" label-for="cust-city">
                <b-form-input
                    id="cust-city"
                    v-model="form.city"
                    type="text"
                    required
                    placeholder="Enter City"></b-form-input>
                    <b-form-invalid-feedback>You must enter a city</b-form-invalid-feedback>
            </b-form-group>
            <b-form-row>
                <b-form-group label="State:" label-for="cust-state" class="col-md-6">
                    <b-form-select
                        v-model="form.selectedState" :options="states" size="lg"
                    ></b-form-select>
                </b-form-group>
                <b-form-group label="Zip Code:" label-for="cust-zip" class="col-md-6">
                    <b-form-input
                        id="cust-zip"
                        v-model="form.zip"
                        type="number"
                        required
                        placeholder="Enter Zip Code"></b-form-input>
                        <b-form-invalid-feedback>You must enter a zip code</b-form-invalid-feedback>
                </b-form-group>
            </b-form-row>
            <b-button type="submit" block variant="primary" :disabled="button.disable">
                <span class="spinner-border spinner-border-sm text-danger" v-show="button.disable"></span>
                {{button.text}}
            </b-button>
        </b-form>
        <b-modal id="modal-parent" title="Instructions" ok-only>
            <p class="my-4">If this is a child site of a larger customer, enter the name or ID of the parent site</p>
        </b-modal>
        <b-modal id="modal-cust-id" title="Instructions" ok-only>
            <p class="my-4 text-center">Enter the unique identifier to be used for this customer.</p>
            <p class="my-4 text-center">This ID should match the ID used in your billing software.</p>
            <p class="my-4 text-center">Leave blank to auto generate an ID.</p>
        </b-modal>
        <b-modal id="select-customer" title="Search For Customer" ref="selectCustomerModal" scrollable @cancel="cancelSelectCustomer">
            <div id="search-results" class="mt-4" v-if="searchResults.length > 0">
                <h4 class="text-center">Select A Customer</h4>
                <b-list-group>
                    <b-list-group-item v-for="res in searchResults" v-bind:key="res.cust_id" class="pointer" @click="selectCustomer(res)">{{res.name}}</b-list-group-item>
                    <b-list-group-item>
                        <div class="text-muted float-left w-auto">Showing items {{searchMeta.from}} to {{searchMeta.to}} of {{searchMeta.total}}</div>
                        <div class="text-muted float-right w-auto">
                            <span class="pointer" v-if="searchMeta.current_page != 1" @click="updatePage(searchMeta.current_page - 1)">
                                <span class="fas fa-angle-double-left"></span> Previous
                            </span>
                            -
                            <span class="pointer" v-if="searchMeta.current_page != searchMeta.last_page" @click="updatePage(searchMeta.current_page + 1)">
                                Next <span class="fas fa-angle-double-right"></span>
                            </span>
                        </div>
                    </b-list-group-item>
                </b-list-group>
            </div>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            validated: false,
            form: {
                cust_id:     null,
                parent_id:   null,
                parent_name: '',
                name:        '',
                dba_name:    '',
                address:     '',
                city:        '',
                zip:         '',
                selectedState: 'CA',
            },
            button: {
                disable: false,
                text: 'Add Customer',
            },
            dup: {
                url:  '',
                name: '',
            },
            state: {
                id:     null,
                parent: null,
            },
            loading: {
                cust_id: false,
                parent:  false,
            },
            searchParam: {
                page: '',
                perPage: 25,
                sortField: 'name',
                sortType: 'asc',
                name: '',
            },
            searchResults: [],
            searchMeta: [],
            states:
            [
                {value: 'AL', text: 'Alabama'},
                {value: 'AK', text: 'Alaska'},
                {value: 'AZ', text: 'Arizona'},
                {value: 'AR', text: 'Arkansas'},
                {value: 'CA', text: 'California'},
                {value: 'CO', text: 'Colorado'},
                {value: 'CT', text: 'Connecticut'},
                {value: 'DE', text: 'Delaware'},
                {value: 'DC', text: 'District Of Columbia'},
                {value: 'FL', text: 'Florida'},
                {value: 'GA', text: 'Georgia'},
                {value: 'HI', text: 'Hawaii'},
                {value: 'ID', text: 'Idaho'},
                {value: 'IL', text: 'Illinois'},
                {value: 'IN', text: 'Indiana'},
                {value: 'IA', text: 'Iowa'},
                {value: 'KS', text: 'Kansas'},
                {value: 'KY', text: 'Kentucky'},
                {value: 'LA', text: 'Louisiana'},
                {value: 'ME', text: 'Maine'},
                {value: 'MD', text: 'Maryland'},
                {value: 'MA', text: 'Massachusetts'},
                {value: 'MI', text: 'Michigan'},
                {value: 'MN', text: 'Minnesota'},
                {value: 'MS', text: 'Mississippi'},
                {value: 'MO', text: 'Missouri'},
                {value: 'MT', text: 'Montana'},
                {value: 'NE', text: 'Nebraska'},
                {value: 'NV', text: 'Nevada'},
                {value: 'NH', text: 'New Hampshire'},
                {value: 'NJ', text: 'New Jersey'},
                {value: 'NM', text: 'New Mexico'},
                {value: 'NY', text: 'New York'},
                {value: 'NC', text: 'North Carolina'},
                {value: 'ND', text: 'North Dakota'},
                {value: 'OH', text: 'Ohio'},
                {value: 'OK', text: 'Oklahoma'},
                {value: 'OR', text: 'Oregon'},
                {value: 'PA', text: 'Pennsylvania'},
                {value: 'RI', text: 'Rhode Island'},
                {value: 'SC', text: 'South Carolina'},
                {value: 'SD', text: 'South Dakota'},
                {value: 'TN', text: 'Tennessee'},
                {value: 'TX', text: 'Texas'},
                {value: 'UT', text: 'Utah'},
                {value: 'VT', text: 'Vermont'},
                {value: 'VA', text: 'Virginia'},
                {value: 'WA', text: 'Washington'},
                {value: 'WV', text: 'West Virginia'},
                {value: 'WI', text: 'Wisconsin'},
                {value: 'WY', text: 'Wyoming'},
            ],
        }
    },
    methods: {
        validateForm(e)
        {
            e.preventDefault();
            console.log(this.form);
            if(this.$refs.newCustomerForm.checkValidity() === false)
            {
                this.validated = true;
            }
            else
            {
                this.button.disable = true;
                this.button.text = 'Processing...';
                axios.post(this.route('customer.id.store'), this.form)
                    .then(res => {
                        console.log(res);
                        if(res.data.success)
                        {
                            window.location.href = this.route('customer.details', [res.data.cust_id, this.dashify(this.form.name)]);
                        }
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            }
        },
        checkID()
        {
            if(this.form.cust_id != '')
            {
                this.loading.cust_id = true;
                axios.get(this.route('customer.check-id', this.form.cust_id))
                    .then(res => {
                        console.log(res.data);
                        this.loading.cust_id = false;
                        if(res.data.dup == true)
                        {
                            this.dup.name = res.data.name;
                            this.dup.url = this.route('customer.details', [this.form.cust_id, this.dashify(res.data.name)]);
                            this.loading.cust_id = false;
                            this.state.id = false;
                        }
                        else if(res.data.dup == false)
                        {
                            this.state.id =  true;
                        }
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            }
            else
            {
                this.dupID = null;
            }
        },
        checkParent()
        {
            if(this.form.parent_name != '')
            {
                this.loading.parent = true;
                this.searchParam.name = this.form.parent_name;
                this.$refs.selectCustomerModal.show();
                axios.get(this.route('customer.search', this.searchParam))
                    .then(res => {
                        this.searchResults = res.data.data;
                        this.searchMeta = res.data.meta;
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            }
        },
        updatePage(newPage)
        {
            this.searchParam.page = newPage;
            this.checkParent();
        },
        selectCustomer(custData)
        {
            this.form.parent_id = custData.cust_id;
            this.form.parent_name = custData.cust_id+': '+custData.name;
            this.searchResults = [];
            this.$refs.selectCustomerModal.hide();
            this.loading.parent = false;
            this.state.parent = true;
        },
        cancelSelectCustomer()
        {
            this.loading.parent = false;
            this.searchParam.name = '';
            this.form.parent_id = null;
            this.form.parent_name = '';
            this.searchResults = [];
            this.state.parent = null;
        }
    }
}
</script>
