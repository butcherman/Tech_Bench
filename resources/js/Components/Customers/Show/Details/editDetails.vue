<template>
    <b-button
        v-if="customerStore.userPerm.edit"
        size="sm"
        variant="warning"
        title="Edit Customer Details"
        pill
        block
        v-b-tooltip.hover
        v-b-modal.edit-customer-modal
    >
        <i class="fas fa-pencil-alt" />
        Edit
        <b-modal
            id="edit-customer-modal"
            ref="edit-customer-modal"
            title="Edit Customer Details"
            hide-footer
        >
            <b-overlay :show="submitted">
                <template #overlay>
                    <form-loader />
                </template>
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form
                        @submit.prevent="handleSubmit(submitForm)"
                        novalidate
                    >
                        <text-input
                            v-model="form.name"
                            label="Customer Name"
                            name="name"
                            placeholder="Enter Customer Name"
                            rules="required"
                        />
                        <text-input
                            v-model="form.dba_name"
                            label="DBA Name"
                            name="dba_name"
                            placeholder="Customer secondary name/AKA name"
                        />
                        <text-input
                            v-model="form.address"
                            label="Customer Address"
                            name="address"
                            rules="required"
                        />
                        <text-input
                            v-model="form.city"
                            label="City"
                            name="city"
                            rules="required"
                        />
                        <b-form-row>
                            <b-col md="6">
                                <all-states v-model="form.state" />
                            </b-col>
                            <b-col md="6">
                                <text-input
                                    v-model="form.zip"
                                    label="Zip Code"
                                    name="zip"
                                    rules="required|numeric"
                                />
                            </b-col>
                        </b-form-row>
                        <submit-button
                            button_text="Update Customer Details"
                            :submitted="submitted"
                        />
                    </b-form>
                </ValidationObserver>
            </b-overlay>
        </b-modal>
    </b-button>
</template>

<script>
    import { useCustomerStore } from '../../../../Stores/customerStore';
    import { mapStores }        from 'pinia';

    export default {
        data() {
            return {
                submitted: false,
                form     : {},
            }
        },
        mounted() {
            this.populateForm();
        },
        computed: {
            ...mapStores(useCustomerStore),
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.form.put(route('customers.update', this.customerStore.custDetails.cust_id), {
                    only     : ['details', 'flash', 'errors'],
                    onError  : (error) => this.eventHub.$emit('validationError', error),
                    onSuccess: ()      => this.$refs['edit-customer-modal'].hide(),
                    onFinish : ()      => this.submitted = false,
                });
            },
            populateForm()
            {
                this.form = this.$inertia.form({
                    name    : this.customerStore.custDetails.name,
                    dba_name: this.customerStore.custDetails.dba_name,
                    address : this.customerStore.custDetails.address,
                    city    : this.customerStore.custDetails.city,
                    state   : this.customerStore.custDetails.state,
                    zip     : this.customerStore.custDetails.zip,
                });
            },
        },
    }
</script>
