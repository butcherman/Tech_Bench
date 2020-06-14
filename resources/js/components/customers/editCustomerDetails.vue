<template>
    <div>
        <b-button class="btn btn-light btn-block" pill size="sm" v-b-modal.edit-customer-modal>Edit Customer</b-button>
        <b-modal title="Edit Customer Details" ref="edit-customer-modal" id="edit-customer-modal" hide-footer>
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
                <b-form @submit="validateForm" ref="edit-customer-form" novalidate :validated="validated">
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
                    <form-submit :submitted="submitted" button_text="Update Customer"></form-submit>
                </b-form>
                <b-button class="mt-2" block @click="linkToParent">{{linkBtnText}}</b-button>
            </b-overlay>
        </b-modal>
        <customer-simple-search ref="cust-search" @selected-customer="selectParent"></customer-simple-search>
    </div>
</template>

<script>
    export default {
        props: {
            details: {
                type:     Object,
                required: true,
            },
            has_parent: {
                required: false,
                default:  false,
            },
        },
        data() {
            return {
                submitted: false,
                validated: false,
                form: {
                    name:     this.details.name,
                    dba_name: this.details.dba_name,
                    address:  this.details.address,
                    city:     this.details.city,
                    state:    this.details.state,
                    zip:      this.details.zip,
                },
            }
        },
        created() {
            //
        },
        mounted() {

        },
        computed: {
            linkBtnText()
            {
                return this.has_parent ? 'Unlink From Parent Site' : 'Link to Parent Site';
            }
        },
        watch: {
             //
        },
        methods: {
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['edit-customer-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                     this.submitted = true;
                     axios.put(this.route('customer.update', this.details.cust_id), this.form)
                         .then(res => {
                             this.submitted = false;
                             this.$emit('updated', this.form);
                             this.$refs['edit-customer-modal'].hide();
                         }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            },
            linkToParent()
            {
                if(!this.has_parent)
                {
                    this.$refs['cust-search'].openForm();
                }
                else
                {
                    var data = {cust_id: this.details.cust_id}
                    axios.get(this.route('customer.link_parent', data))
                        .then(res => {
                            this.eventHub.$emit('parent-linked', null);
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }

                this.$refs['edit-customer-modal'].hide();
            },
            selectParent(parent)
            {
                var data = {cust_id: this.details.cust_id, parent_id: parent.cust_id}
                axios.get(this.route('customer.link_parent', data))
                        .then(res => {
                            this.eventHub.$emit('parent-linked', parent);
                        }).catch(error => this.eventHub.$emit('axiosError', error));
            }
        },
    }
</script>
