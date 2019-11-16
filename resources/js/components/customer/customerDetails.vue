<template>
    <div>
        <div class="row">
            <div class="col-md-8">
                <h3>
                    <span :class="classFav" :title="markFav" v-b-tooltip.hover @click="toggleFav"></span>
                    {{details.name}}
                </h3>
                <h5>{{details.dba_name}}</h5>
                <address>
                    <div class="float-left">
                        <span class="ti-location-pin"></span>
                    </div>
                    <a :href="url" target="_blank" id="addr-span" class="float-left">
                        {{details.address}}<br />
                        {{details.city}}, {{details.state}} &nbsp;{{details.zip}}
                    </a>
                </address>
            </div>
            <div class="col-md-4">
                <div class="float-right">
                    <button class="btn btn-light btn-block" v-b-modal.details-edit-modal>Edit Customer</button>
                    <button class="btn btn-danger btn-block" @click="confirmDestroy">Deactivate Customer</button>
                </div>
            </div>
        </div>
        <b-modal id="details-edit-modal" title="Edit Customer" ref="details-edit-modal" hide-footer centered>
            <b-form @submit="updateCustomer" novalidate :validated="validated" ref="editCustomerForm">
                <b-form-group
                    label="Customer Name:"
                    label-for="cust-name"
                >
                    <b-form-input
                        id="cust-name"
                        v-model="form.name"
                        type="text"
                        required
                        placeholder="Enter Customer Name"></b-form-input>
                        <b-form-invalid-feedback>You must enter a customer name</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group
                    label="DBA Name:"
                    label-for="dba-name"
                >
                    <b-form-input
                        id="dba-name"
                        v-model="form.dba_name"
                        type="text"
                        placeholder="Enter Customer DBA/AKA Name"></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Address:"
                    label-for="cust-address"
                >
                    <b-form-input
                        id="cust-address"
                        v-model="form.address"
                        type="text"
                        required
                        placeholder="Enter Customer Address"></b-form-input>
                        <b-form-invalid-feedback>You must enter a customer address</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group
                    label="City:"
                    label-for="cust-city"
                >
                    <b-form-input
                        id="cust-city"
                        v-model="form.city"
                        type="text"
                        required
                        placeholder="Enter City"></b-form-input>
                        <b-form-invalid-feedback>You must enter a city</b-form-invalid-feedback>
                </b-form-group>
                <b-form-row>
                    <b-form-group
                        label="State:"
                        label-for="cust-state"
                        class="col-md-6"
                    >
                        <b-form-select
                            v-model="form.state" :options="form.states"
                        ></b-form-select>
                    </b-form-group>
                    <b-form-group
                        label="Zip Code:"
                        label-for="cust-zip"
                        class="col-md-6"
                    >
                        <b-form-input
                            id="cust-zip"
                            v-model="form.zip"
                            type="number"
                            required
                            placeholder="Enter Zip Code"></b-form-input>
                            <b-form-invalid-feedback>You must enter a zip code</b-form-invalid-feedback>
                    </b-form-group>
                </b-form-row>
                <b-button type="submit" variant="primary" :disabled="button.dis" block>
                    <span class="spinner-border spinner-border-sm text-danger" v-show="button.dis"></span>
                    {{button.text}}
                </b-button>
            </b-form>
        </b-modal>
        <b-modal id="loading-modal" size="sm" ref="loading-modal" hide-footer hide-header hide-backdrop centered>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        </b-modal>
    </div>
</template>

<script>
export default {
    props: [
        'cust_details',
        'is_fav',
        'can_del',
    ],
    data() {
        return {
            validated: false,
            details: this.cust_details,
            url: '',
            isFav: this.is_fav,
            classFav: this.is_fav ? 'ti-bookmark-alt bookmark-checked' : 'ti-bookmark bookmark-unchecked',
            markFav: this.is_fav ? 'Remove From Favorites' : 'Add to Favorites',
            button: {
                dis: false,
                text: 'Update Customer Data'
            },
            form: {
                name: this.cust_details.name,
                dba_name: this.cust_details.dba_name,
                address: this.cust_details.address,
                city: this.cust_details.city,
                state: this.cust_details.state,
                zip: this.cust_details.zip,
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
            }
        }
    },
    created()
    {
        this.setAddressURL();
    },
    methods: {
        setAddressURL()
        {
            this.url = 'https://maps.google.com/?q='+encodeURI(this.details.address+','+this.details.city+','+this.details.state);
        },
        toggleFav()
        {
            this.classFav = 'spinner-grow text-light';
            if(this.isFav)
            {
                axios.get(this.route('customer.toggle-fav', ['remove', this.cust_details.cust_id]))
                    .then(res => {
                        this.isFav = false;
                        this.classFav  = 'ti-bookmark bookmark-unchecked';
                        this.markFav = 'Add To Favorites'; //  : 'Add to Favorites',
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            }
            else
            {
                axios.get(this.route('customer.toggle-fav', ['add', this.cust_details.cust_id]))
                    .then(res => {
                        this.isFav = true;
                        this.classFav  = 'ti-bookmark-alt bookmark-checked';
                        this.markFav = 'Remove From Favorites';
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            }
        },
        updateCustomer(e)
        {
            e.preventDefault();
            if(this.$refs.editCustomerForm.checkValidity() === false)
            {
                this.validated = true;
            }
            else
            {
                this.button.dis = true;
                this.button.text = 'Updating Customer';
                axios.put(this.route('customer.id.update', this.cust_details.cust_id), this.form)
                    .then(res => {
                        console.log(res);
                        if(res.data.success === true)
                        {
                            this.validated = false;
                            this.details = this.form;
                            this.button.dis = false;
                            this.button.text = 'Update Customer';
                            this.$refs['details-edit-modal'].hide();
                        }
                        else
                        {
                            alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error);
                        }
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            }
        },
        confirmDestroy()
        {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to deactivate this customer.', {
            title: 'Please Confirm',
            size: 'sm',
            buttonSize: 'sm',
            okVariant: 'danger',
            okTitle: 'YES',
            cancelTitle: 'NO',
            footerClass: 'p-2',
            hideHeaderClose: false,
            centered: true
            })
            .then(value => {
                // console.log(value);
                if(value)
                {
                    this.$refs['loading-modal'].show();
                    axios.delete(this.route('customer.id.destroy', this.cust_details.cust_id))
                    .then(res => {
                        console.log(res);
                        window.location.href = this.route('customer.index');
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                }
            })
            .catch(error => {
                alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error);
            })
        }
    }
}
</script>
