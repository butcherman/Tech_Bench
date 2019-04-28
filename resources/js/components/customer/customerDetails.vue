<template>
    <div>
        <h2>
            <span :class="isFav" @click="toggleFav"><i class="fa fa-bookmark"></i></span>
            {{form.name}}</h2>
        <h5>{{form.dba_name}}</h5>
        <address>
            <a :href="url" target="_blank" id="addr-span">
                {{form.address}}<br />
                {{form.city}}, {{form.state}} &nbsp;{{form.zip}}
            </a>
            <span class="text-muted pointer" id="edit-customer" @click="openEditModal"><i class="fa fa-pencil" aria-hidden="true"></i></span>
        </address>
        <b-modal id="details-edit-moadl" title="Edit Customer" ref="detailsEditModal" @close="getDetails" hide-footer centered>
            <b-form @submit="updateCustomer" @reset="resetForm">
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
                    </b-form-group>
                </b-form-row>
                <b-button type="submit" variant="info" block>Update Customer Data</b-button>
                <div class="row justify-content-center pad-top">
                    <div class="col-md-6">
                        <b-button type="reset" variant="warning" block>Reset</b-button>
                    </div>
                </div>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
export default {
    props: [
        'cust_id',
        'is_fav',
        'fav_route',
        'show_route',
        'edit_route',
    ],
    data() {
        return {
            url: 'https://maps.google.com/?q='+encodeURI(this.address+','+this.city+','+this.state),
            isFav: this.is_fav ? 'bookmark-checked' : 'bookmark-unchecked',
            form: {
                name: '',
                dba_name: '',
                address: '',
                city: '',
                state: '',
                zip: '',
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
        this.getDetails();
    },
    methods: {
        toggleFav()
        {
            if(this.isFav === 'bookmark-unchecked')
            {
                axios.get(this.fav_route.replace(':action', 'add'))
                    .then(res => {
                        this.favChk = true;
                        this.isFav  = 'bookmark-checked';
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            }
            else
            {
                axios.get(this.fav_route.replace(':action', 'remove'))
                    .then(res => {
                        this.favChk = false;
                        this.isFav  = 'bookmark-unchecked';
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            }
        },
        getDetails()
        {
            axios.get(this.show_route)
                .then(res => {
                    this.form.name     = res.data.name;
                    this.form.dba_name = res.data.dba_name;
                    this.form.address  = res.data.address;
                    this.form.city     = res.data.city;
                    this.form.state    = res.data.state;
                    this.form.zip      = res.data.zip;
                }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        },
        openEditModal()
        {
            this.$refs.detailsEditModal.show();
        },
        updateCustomer(e)
        {
            e.preventDefault();
            axios.put(this.show_route, this.form)
                .then(res => {
                    console.log(res);
                    if(res.data.success === true)
                    {       
                        this.$refs.detailsEditModal.hide();
                    }
                    else
                    {
                        alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error);
                    }
                }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        },
        resetForm(e)
        {
            e.preventDefault();
            this.getDetails();
        }
    }
}
</script>
