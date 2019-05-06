<template>
    <div>
        <b-form @submit="submitForm">
           
            <b-form-group
                label="Customer ID:"
                label-for="cust-id"
            >
                <b-form-input
                    id="cust-id"
                    v-model="form.custID"
                    type="number"
                    class="col-md-6"
                    :state="dupID"
                    @blur="checkID"
                    required
                    placeholder="Enter Customer ID Number"></b-form-input>
                <b-form-invalid-feedback :state="dupID">
                    This Customer ID Is Already Taken.  Click <a :href="dupURL">here</a> to view customer.
                </b-form-invalid-feedback>
                <b-form-valid-feedback :state="dupID">
                    This Customer ID Is Available
                </b-form-valid-feedback>
            </b-form-group>
            <b-form-group
                label="Customer Name:"
                label-for="cust-name"
            >
                <b-form-input
                    id="cust-name"
                    v-model="form.custName"
                    type="text"
                    required
                    placeholder="Enter Customer Name"></b-form-input>
            </b-form-group>
            <b-form-group
                label="DBA Name:"
                label-for="dba-name"
            >
                <b-form-input
                    id="dba-name"
                    v-model="form.custDBA"
                    type="text"
                    placeholder="Enter Customer DBA/AKA Name"></b-form-input>
            </b-form-group>
            <b-form-group
                label="Address:"
                label-for="cust-address"
            >
                <b-form-input
                    id="cust-address"
                    v-model="form.custAddr"
                    type="text"
                    required
                    placeholder="Enter Customer Address"></b-form-input>
            </b-form-group>
            <b-form-group
                label="City:"
                label-for="cust-city"
            >
                <b-form-input
                    id="cust-city"
                    v-model="form.custCity"
                    type="text"
                    required
                    placeholder="Enter City"></b-form-input>
            </b-form-group>
            <b-form-row>
                <b-form-group
                    label="State:"
                    label-for="cust-state"
                    class="col-md-6"
                >
                    <b-form-select
                        v-model="form.selectedState" :options="form.states"
                    ></b-form-select>
                </b-form-group>
                <b-form-group
                    label="Zip Code:"
                    label-for="cust-zip"
                    class="col-md-6"
                >
                    <b-form-input
                        id="cust-zip"
                        v-model="form.custZip"
                        type="number"
                        required
                        placeholder="Enter Zip Code"></b-form-input>
                </b-form-group>
                <b-button type="submit" block variant="primary" :disabled="form.button.dis">{{form.button.text}}</b-button>
            </b-form-row>
        </b-form>
    </div>
</template>

<script>
export default {
    props: [
        'check_id_route',
        'submit_route',
    ],
    data() {
        return {
            dupURL: '',
            dupID: null,
            form: {
                custID:   '',
                custName: '',
                custDBA:  '',
                custAddr: '',
                custCity: '',
                custZip:  '',
                states: [
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
                selectedState: 'CA',
                button: {
                    text: 'Submit New Customer',
                    dis: false,
                }
            }
        }
    },
    methods: {
        submitForm(e)
        {
            e.preventDefault();
            
            axios.post(this.submit_route, this.form)
                .then(res => {
                    console.log(res);
                    if(res.data.success)
                    {
                        window.location.href = res.data.url;
                    }
                })
                .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            
        },
        checkID()
        {
            if(this.form.custID != '')
            { 
                axios.get(this.check_id_route.replace(':id', this.form.custID))
                    .then(res => {
                        if(res.data.dup == true)
                        {
                            this.dupURL = res.data.url
                            this.dupID = false
                        }
                        else if(res.data.dup == false)
                        {
                            this.dupID =  true;
                        }
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
