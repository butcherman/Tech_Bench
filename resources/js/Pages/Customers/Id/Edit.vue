<template>
    <div>
        <div class="row grid-margin">
            <div class="col-md-12">
                <h4 class="text-center text-md-left">Modify Customer ID for - {{details.name}}</h4>
            </div>
        </div>
        <div class="row justify-content-center grid-margins">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <b-overlay :show="submitted">
                            <template #overlay>
                                <form-loader></form-loader>
                            </template>
                            <ValidationObserver v-slot="{handleSubmit}">
                                <div class="row grid-margin">
                                    <div class="col text-center">
                                        <strong>Current ID -</strong> {{details.cust_id}}
                                    </div>
                                </div>
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input v-model="form.cust_id" name="cust_id" placeholder="Enter Customer ID Number" rules="required|numeric|unique-customer" mode="lazy">
                                        <template #label>
                                            New Customer ID:
                                            <i class="far fa-question-circle pointer text-warning" title="Click for More Information" v-b-tooltip:hover @click="$bvToast.show('cust-id-toast')"></i>
                                        </template>
                                    </text-input>
                                    <submit-button button_text="Update Customer ID" :submitted="submitted"></submit-button>
                                </b-form>
                            </ValidationObserver>
                        </b-overlay>
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
    import App from '../../../Layouts/app';

    export default {
        layout: App,
        props: {
            details: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                form: {
                    current_id: this.details.cust_id,
                    cust_id:    null,
                }
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.$inertia.put(route('admin.cust.change-id.update', this.details.cust_id), this.form);
            }
        }
    }
</script>
