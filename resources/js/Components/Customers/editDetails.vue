<template>
    <b-button variant="warning" block pill size="sm" title="Edit Customer Details" v-b-tooltip.hover v-b-modal.edit-customer-modal>
        <i class="fas fa-pencil-alt"></i>
        Edit
        <b-modal title="Edit Customer Details" ref="edit-customer-modal" id="edit-customer-modal" hide-footer>
            <b-overlay :show="submitted">
                <template #overlay>
                    <form-loader></form-loader>
                </template>
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                        <text-input v-model="form.name" label="Customer Name" name="name" placeholder="Enter Customer Name" rules="required"></text-input>
                        <text-input v-model="form.dba_name" label="DBA Name" name="dba_name" placeholder="Customer secondary name/AKA name"></text-input>
                        <text-input v-model="form.address" label="Customer Address" name="address" rules="required"></text-input>
                        <text-input v-model="form.city" label="City" name="city" rules="required"></text-input>
                        <b-form-row>
                            <b-col md="6">
                                <all-states v-model="form.state"></all-states>
                            </b-col>
                            <b-col md="6">
                                <text-input v-model="form.zip" label="Zip Code" name="zip" rules="required|numeric"></text-input>
                            </b-col>
                        </b-form-row>
                        <submit-button button_text="Update Customer Details" :submitted="submitted"></submit-button>
                    </b-form>
                </ValidationObserver>
            </b-overlay>
        </b-modal>
    </b-button>
</template>

<script>
    export default {
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
                    name:     this.details.name,
                    dba_name: this.details.dba_name,
                    address:  this.details.address,
                    city:     this.details.city,
                    state:    this.details.state,
                    zip:      this.details.zip,
                }
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.$inertia.put(route('customers.update', this.details.cust_id), this.form,
                {
                    onFinish: () => {
                        this.submitted = false;
                        this.$refs['edit-customer-modal'].hide();
                    }
                });
            }
        },
    }
</script>
