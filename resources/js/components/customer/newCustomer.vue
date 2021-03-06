<template>
    <b-overlay :show="submitted">
        <template v-slot:overlay>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Creating Customer</h4>
        </template>
        <b-form @submit="validateForm" :validated="validated" ref="newCustomerForm" novalidate>
            <fieldset :disabled="submitted">
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
            </fieldset>
            <form-submit
                button_text="Create Customer"
                :submitted="submitted"
            ></form-submit>
        </b-form>
        <b-modal id="modal-parent" title="Instructions" ok-only>
            <p class="my-4">If this is a child site of a larger customer, enter the name or ID of the parent site</p>
        </b-modal>
        <b-modal id="modal-cust-id" title="Instructions" ok-only>
            <p class="my-4 text-center">Enter the unique identifier to be used for this customer.</p>
            <p class="my-4 text-center">This ID should match the ID used in your billing software.</p>
            <p class="my-4 text-center">Leave blank to auto generate an ID.</p>
        </b-modal>
        <customer-search @selectedCust="updateParent" @selectCanceled="cancelParent" :show_form="showSearch" :search_name="form.parent_name"></customer-search>
    </b-overlay>
</template>

<script>
export default {
    data() {
        return {
            validated: false,
            submitted: false,
            showSearch: false,
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
            if(this.$refs.newCustomerForm.checkValidity() === false)
            {
                this.validated = true;
            }
            else
            {
                this.submitted = true;
                axios.post(this.route('customer.id.store'), this.form)
                    .then(res => {
                        if(res.data.success)
                        {
                            window.location.href = this.route('customer.details', [res.data.cust_id, this.dashify(this.form.name)]);
                        }
                    })
                    .catch(error => this.$bvModal.msgBoxOk('Create Customer operation failed.  Please try again later.'));
            }
        },
        checkID()
        {
            if(this.form.cust_id != '')
            {
                this.loading.cust_id = true;
                axios.get(this.route('customer.check-id', this.form.cust_id))
                    .then(res => {
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
                    .catch(error => this.$bvModal.msgBoxOk('Check Customer ID operation failed.  Please try again later.'));
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
                this.showSearch = true;
            }
            else
            {
                this.form.parent_id = null;
                this.form.parent_name = '';
            }
        },
        updateParent(custData)
        {
            this.form.parent_id = custData.cust_id;
            this.form.parent_name = custData.cust_id+': '+custData.name;
            this.searchResults = [];
            this.loading.parent = false;
            this.state.parent = true;
            this.showSearch = false;
        },
        cancelParent()
        {
            this.showSearch = false;
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
