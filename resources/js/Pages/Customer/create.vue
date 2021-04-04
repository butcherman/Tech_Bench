<template>
    <div>
        <div class="row grid-margin">
            <div class="col-md-12">
                <h4 class="text-center text-md-left">Create New Customer</h4>
            </div>
        </div>
        <div class="row justify-content-center grid-margins">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                <b-form-row>
                                    <b-col md="6">
                                        <text-input v-model="form.cust_id" label="Customer ID" name="cust_id" placeholder="Enter Customer ID Number" @blur="checkCustId">
                                            <template #label>
                                                Customer ID:
                                                <i class="far fa-question-circle pointer text-warning" title="Click for More Information" v-b-tooltip:hover @click="$bvToast.show('cust-id-toast')"></i>
                                            </template>
                                        </text-input>
                                    </b-col>
                                    <b-col md="6">
                                        <text-input v-model="form.parent_id" label="Parent Customer" name="parent_id" placeholder="(optional)"></text-input>
                                    </b-col>
                                </b-form-row>





                                <submit-button button_text="Login" :submitted="submitted"></submit-button>

                            </b-form>
                        </ValidationObserver>
                    </div>
                </div>
            </div>
        </div>
        <b-toast id="cust-id-toast" title="Instructions for Customer ID" variant="info">
            <p class="my-4 text-center">Enter the unique identifier to be used for this customer.</p>
            <p class="my-4 text-center">This ID should match the ID used in your billing software.</p>
            <p class="my-4 text-center">Leave blank to auto generate an ID.</p>
        </b-toast>
    </div>
</template>

<script>
    import App from '../../Layouts/app';

    export default {
        layout: App,
        props: {
            //
        },
        data() {
            return {
                form: {
                    cust_id: '',
                    parent_id: '',
                    name: '',
                    dba_name: '',
                    address: '',
                    city: '',
                    state: '',
                    zip: '',

                },
                submitted: false,
            }
        },
        created() {
            //
        },
        mounted() {
             //
        },
        computed: {
             //
        },
        watch: {
             //
        },
        methods: {
            //
            submitForm()
            {
                console.log(this.form);
            },
            checkCustId()
            {
                console.log('check customer id');
            }
        }
    }
</script>
