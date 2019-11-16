<template>
    <div>
        <b-form @submit="validateForm" novalidate :validated="validated" ref="newCustomerForm">
            <input type="hidden" name="_token" :value="token" />
            <b-form-group label="Customer ID:" label-for="cust_id">
                <b-form-input
                    id="cust_id"
                    type="number"
                    class="col-md-6"
                    v-model="form.cust_id"
                    :state="dupID"
                    @blur="checkID"
                    required
                    placeholder="Enter Customer ID Number"></b-form-input>
                    <b-form-invalid-feedback>This Customer ID Is Already Taken by {{dupName}}.  Click <a :href="dupURL">here</a> to view customer.</b-form-invalid-feedback>
            </b-form-group>
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
            <b-button type="submit" block variant="primary" :disabled="button.dis">{{button.text}}</b-button>
        </b-form>
        <b-modal id="loading-modal" size="sm" ref="loading-modal" hide-footer hide-header hide-backdrop centered>
            Verifying Customer ID is Available
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            token: window.techBench.csrfToken,
            dupID: null,
            dupURL: '',
            dupName: '',
            validated: false,
            form: {
                cust_id:   '',
                name: '',
                dba_name:  '',
                address: '',
                city: '',
                zip:  '',

                selectedState: 'CA',

            },
            button: {
                text: 'Submit New Customer',
                dis: false,
            },
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
                this.button.text = "Loading...";
                this.button.dis = true;
                axios.post(this.route('customer.id.store'), this.form)
                .then(res => {
                    console.log(res);
                    if(res.data.success)
                    {
                        window.location.href = this.route('customer.details', [this.form.cust_id, this.dashify(this.form.name)]);
                    }
                })
                .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            }
        },
        checkID()
        {
            if(this.form.cust_id != '')
            {
                this.$refs['loading-modal'].show();
                axios.get(this.route('customer.check-id', this.form.cust_id))
                    .then(res => {
                        if(res.data.dup == true)
                        {
                            this.dupName = res.data.name;
                            this.dupURL = this.route('customer.details', [this.form.cust_id, this.dashify(res.data.name)]);
                            this.dupID = false
                        }
                        else if(res.data.dup == false)
                        {
                            this.dupID =  true;
                        }
                        this.$refs['loading-modal'].hide();
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            }
            else
            {
                this.dupID = null;
            }
        }
    }
}
</script>
