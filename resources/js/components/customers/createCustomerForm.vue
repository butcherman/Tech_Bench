<template>
    <b-overlay :show="submitted">
        <template v-slot:overlay>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Processing</h4>
        </template>
        <b-form @submit="validateForm" :validated="validated" novalidate ref="new-customer-form">
            <b-form-row>
                <b-form-group label-for="cust_id" class="col-md-6">
                    <template slot="label">
                        Customer ID:
                        <i class="far fa-question-circle pointer" title="Click for More Information" v-b-tooltip:hover @click="$bvToast.show('cust-id-toast')"></i>
                    </template>
                    <b-form-input
                        id="cust_id"
                        type="number"
                        v-model="form.cust_id"
                        placeholder="Enter Customer ID Number"
                        :state="validate.state.cust_id"
                        :class="validate.loading.cust_id ? 'loading' : ''"
                        @blur="checkCustID"
                    ></b-form-input>
                    <b-form-invalid-feedback>
                        <div v-for="msg in validate.message.cust_id" :key="msg" v-html="msg"></div>
                    </b-form-invalid-feedback>
                </b-form-group>
                <b-form-group  label-for="parent_id" class="col-md-6">
                    <template slot="label">
                        Parent Site ID:
                        <i class="far fa-question-circle pointer" title="Click for More Information" v-b-tooltip:hover @click="$bvToast.show('parent-id-toast')"></i>
                    </template>
                    <b-input-group>
                        <b-form-input
                            id="parent_id"
                            type="text"
                            v-model="form.parent_name"
                            placeholder="(optional)"
                            :state="validate.state.parent_id"
                            :class="validate.loading.parent_id"
                            @blur="findParent"
                        ></b-form-input>
                        <b-input-group-append>
                            <b-button varient="primary" @click="$refs['cust-search'].openForm(form.parent_name ? form.parent_name : null)"><span class="fas fa-search"></span></b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </b-form-row>
            <b-form-group label="Customer Name:" label-for="cust-name">
                <b-form-input
                    id="cust-name"
                    type="text"
                    v-model="form.name"
                    required
                    placeholder="Enter Customer Name"></b-form-input>
                    <b-form-invalid-feedback>The Customer Name is required</b-form-invalid-feedback>
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
                    <b-form-invalid-feedback>The Customer Address is required</b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="City:" label-for="cust-city">
                <b-form-input
                    id="cust-city"
                    v-model="form.city"
                    type="text"
                    required
                    placeholder="Enter City"></b-form-input>
                    <b-form-invalid-feedback>City is required</b-form-invalid-feedback>
            </b-form-group>
            <b-form-row>
                <all-states v-model="form.state" class="col-md-6"></all-states>
                <b-form-group label="Zip Code:" label-for="cust-zip" class="col-md-6">
                    <b-form-input
                        id="cust-zip"
                        v-model="form.zip"
                        type="number"
                        required
                        placeholder="Enter Zip Code"></b-form-input>
                        <b-form-invalid-feedback>Zip Code is required</b-form-invalid-feedback>
                </b-form-group>
            </b-form-row>
            <form-submit :submitted="submitted" button_text="Create Customer"></form-submit>
        </b-form>
        <b-toast id="cust-id-toast" title="Instructions for Customer ID" variant="info">
            <p class="my-4 text-center">Enter the unique identifier to be used for this customer.</p>
            <p class="my-4 text-center">This ID should match the ID used in your billing software.</p>
            <p class="my-4 text-center">Leave blank to auto generate an ID.</p>
        </b-toast>
        <b-toast id="parent-id-toast" title="Instruction for Parent ID" variant="info">
            <p class="my-4">If this is a child site of a larger customer, enter the name or ID of the parent site</p>
        </b-toast>
        <customer-simple-search ref="cust-search" @selected-customer="selectParent"></customer-simple-search>
    </b-overlay>
</template>

<script>
    export default {
        data() {
            return {
                validated:  false,
                submitted:  false,
                showSearch: false,
                form: {
                    cust_id:     '',
                    parent_id:   '',
                    parent_name: '',
                    name:        '',
                    dba_name:    '',
                    address:     '',
                    city:        '',
                    state:       '',
                    zip:         '',
                },
                validate: {
                    state: {
                        cust_id:   null,
                        parent_id: null,
                    },
                    message: {
                        cust_id:   [],
                        parent_id: [],
                    },
                    loading: {
                        cust_id:   false,
                        parent_id: false,
                    },
                },
            }
        },
        methods: {
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['new-customer-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                     this.submitted = true;
                     axios.post(this.route('customer.store'), this.form)
                         .then(res => {
                             location.href = this.route('customer.details', [res.data.cust_id, encodeURI(this.dashify(this.form.name))])
                         }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            },
            checkCustID()
            {
                if(this.form.cust_id != '')
                {
                    this.validate.loading.cust_id = true;
                    axios.get(this.route('customer.check_id', this.form.cust_id))
                        .then(res => {
                            if(res.data.duplicate)
                            {
                                this.validate.state.cust_id = false;
                                this.validate.message.cust_id = ['This ID is taken by <a href="'+res.data.url+'">'+res.data.name+'</a>'];
                                this.validate.loading.cust_id = false;
                            }
                            else
                            {
                                this.validate.state.cust_id = true;
                                this.validate.loading.cust_id = false;
                            }
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            },
            findParent()
            {
                if(this.form.parent_name != '')
                {
                    this.$refs['cust-search'].openForm(this.form.parent_name);
                }
                else
                {
                    this.form.parent_id = null;
                }
            },
            selectParent(data)
            {
                this.form.parent_id = data.cust_id;
                this.form.parent_name = data.cust_id+': ('+data.name+')';
            }
        }
    }
</script>
