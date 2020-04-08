<template>
    <div>
        <customer-search :show_form="true" title="Search for Customer to Modify" @selectedCust="fillForm"></customer-search>
        <b-form @submit="updateCustomer" v-show="form.cust_id" ref="customerIDForm" novalidate :validated="validated">
            <h5 class="text-center">
                Update Customer ID for <span class="text-muted">{{name}}</span>
            </h5>
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
            <form-submit
                :button_text="buttonText"
                :submitted="submitted"
            ></form-submit>
        </b-form>
    </div>
</template>

<script>
export default {
    props: [
        //
    ],
    data() {
        return {
            //
            buttonText: 'Update Customer ID',
            submitted: false,
            name: null,
            validated: false,
            form: {
                original_id: null,
                cust_id: null,
            },
            loading: {
                cust_id: false,
            },
            dup: {
                url:  '',
                name: '',
            },
            state: {
                id:     null,
            },
        }
    },
    methods: {
        fillForm(cust)
        {
            this.form.original_id = cust.cust_id;
            this.name = cust.name;
            this.form.cust_id = cust.cust_id;
        },
        checkID()
        {
            if(this.form.cust_id != '' && this.form.cust_id != this.form.original_id)
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
                    .catch(error => this.$bvModal.msgBoxOk('We are unable to verify if this ID is in use'));
            }
            else
            {
                this.dupID = null;
            }
        },
        updateCustomer(e)
        {
            e.preventDefault();

            if(this.$refs.customerIDForm.checkValidity() === false)
            {
                this.validated = true;
            }
            else if(this.form.cust_id == this.form.original_id)
            {
                this.$bvModal.msgBoxOk('Customer ID has not been changed');
            }
            else
            {
                this.submitted = true;
                axios.post(this.route('admin.submitCustomerID'), this.form)
                    .then(res => {
                        console.log(res);
                        if(res.data.success == true)
                        {
                            this.$bvModal.msgBoxOk('Customer ID Updated')
                                .then(
                                    window.location.href = this.route('admin.index')
                                );
                        }
                        else
                        {
                            this.$bvModal.msgBoxOk('Update Customer ID Operation Failed.  Try again later');
                        }
                    }).catch(error => this.$bvModal.msgBoxOk('Update Customer ID Operation Failed.  Try again later'));
            }
        }
    }
}
</script>
