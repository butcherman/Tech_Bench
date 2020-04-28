<template>
    <div>
        <div class="row">
            <div class="col-md-8">
                <h3>
                    <span :class="classFav" :title="markFav" v-b-tooltip.hover @click="toggleFav"></span>
                    {{details.name}}
                </h3>
                <h5>{{details.dba_name}}</h5>
                <h5 v-if="details.parent_id">Child of - <a :href="route('customer.details', [details.parent_customer.cust_id, dashify(details.parent_customer.name)])" target="_blank">{{details.parent_customer.name}}</a></h5>
                <address>
                    <div class="float-left">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <a :href="url" target="_blank" id="addr-span" class="float-left ml-2" title="Click for Google Maps" v-b-tooltip.hover>
                        {{details.address}}<br />
                        {{details.city}}, {{details.state}} &nbsp;{{details.zip}}
                    </a>
                </address>
            </div>
            <div class="col-md-4 mt-md-0 mt-4">
                <div class="float-md-right">
                    <b-button class="btn btn-light btn-block" pill size="sm" v-b-modal.details-edit-modal>Edit Customer</b-button>
                    <b-button class="btn btn-danger btn-block" pill size="sm" v-show="allowDeact" @click="deactivateCust">Deactivate Customer</b-button>
                </div>
            </div>
        </div>
        <b-modal id="details-edit-modal" title="Edit Customer" ref="details-edit-modal" hide-footer centered>
            <b-overlay :show="submitted">
                <template v-slot:overlay>
                    <atom-spinner
                        :animation-duration="1000"
                        :size="60"
                        color="#ff1d5e"
                        class="mx-auto"
                    />
                    <h4 class="text-center">Updating Customer</h4>
                </template>
                <b-form @submit="validateForm" novalidate :validated="validated" ref="editCustomerForm">
                    <fieldset :disabled="submitted">
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
                    </fieldset>
                    <form-submit
                        class="mt-3"
                        button_text="Update Customer"
                        :submitted="submitted"
                    ></form-submit>
                    <b-button v-if="!cust_details.child_count" class="mt-2" block @click="linkToParent">{{linkBtn}}</b-button>
                </b-form>
            </b-overlay>
        </b-modal>
        <customer-search :show_form="showSearch" @selectedCust="updateCust"></customer-search>
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
                details:    this.cust_details,
                url: '',
                isFav:      this.is_fav,
                classFav:   this.is_fav ? 'fas fa-bookmark bookmark-checked' : 'far fa-bookmark bookmark-unchecked',
                markFav:    this.is_fav ? 'Remove From Favorites' : 'Add to Favorites',
                validated:  false,
                submitted:  false,
                showSearch: false,
                linkBtn:    'Link to Parent Site',
                linkData:   {},
                allowDeact: false,
                form: {
                    name:     this.cust_details.name,
                    dba_name: this.cust_details.dba_name,
                    address:  this.cust_details.address,
                    city:     this.cust_details.city,
                    state:    this.cust_details.state,
                    zip:      this.cust_details.zip,
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
                },
            }
        },
        mounted() {
            this.loading = false;
            this.init();
        },
        methods: {
            toggleFav()
            {
                this.classFav = 'spinner-grow text-light';
                if(this.isFav)
                {
                    axios.get(this.route('customer.toggle-fav', ['remove', this.cust_details.cust_id]))
                        .then(res => {
                            this.isFav = false;
                            this.classFav  = 'far fa-bookmark bookmark-unchecked';
                            this.markFav = 'Add To Favorites';
                        })
                        .catch(error => this.$bvModal.msgBoxOk('Update favorite failed.  Please try again later.'));
                }
                else
                {
                    axios.get(this.route('customer.toggle-fav', ['add', this.cust_details.cust_id]))
                        .then(res => {
                            this.isFav = true;
                            this.classFav  = 'fas fa-bookmark bookmark-checked';
                            this.markFav = 'Remove From Favorites';
                        })
                        .catch(error => this.$bvModal.msgBoxOk('Update favorite failed.  Please try again later.'));
                }
            },
            init()
            {
                this.url = 'https://maps.google.com/?q='+encodeURI(this.details.address+','+this.details.city+','+this.details.state);
                this.linkBtn = this.details.parent_id == null ? 'Link to Parent Site' : 'Unlink From Parent Site';
                if(this.details.parent_id == null && this.details.child_count == 0 && this.can_del == true)
                {
                    this.allowDeact = true;
                }
                else
                {
                    this.allowDeact = false;
                }
            },
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs.editCustomerForm.checkValidity() === false)
                {
                    this.validated = true;
                }
                else
                {
                    this.submitted = true;
                    axios.put(this.route('customer.id.update', this.cust_details.cust_id), this.form)
                        .then(res => {
                            if(res.data.success === true)
                            {
                                var parent = this.details.parent_customer;
                                var parentID = this.details.parent_id;
                                this.validated = false;
                                this.details = this.form;
                                this.details.parent_customer = parent;
                                this.details.parent_id = parentID;
                                this.$refs['details-edit-modal'].hide();
                                this.submitted = false;
                            }
                            else
                            {
                                this.$bvModal.msgBoxOk('Update Customer Information operation failed.  Please try again later.')
                            }
                        }).catch(error => this.$bvModal.msgBoxOk('We are having problems updating the customer at this time.  Please try again later.'));
                }
            },
            linkToParent()
            {
                if(this.details.parent_id == null)
                {
                    this.showSearch = true;
                }
                else
                {
                    this.unlinkCust();
                }
            },
            updateCust(data)
            {
                this.linkData  = data;
                this.submitted = true;

                if(data.cust_id == this.cust_details.cust_id)
                {
                    this.$bvModal.msgBoxOk('Cannot link a site to itself');
                    this.showSearch = false;
                    this.submitted  = false;
                    this.$refs['details-edit-modal'].hide();
                }
                else
                {
                    axios.post(this.route('customer.linkParent'), {'parent_id': data.cust_id, 'cust_id': this.cust_details.cust_id})
                        .then(res => {
                            this.showSearch = false;
                            this.submitted  = false;
                            this.$refs['details-edit-modal'].hide();
                            this.details.parent_customer = {
                                cust_id: this.linkData.cust_id,
                                name: this.linkData.name,
                            };
                            this.details.parent_id = this.linkData.cust_id;
                            this.init();
                            this.$emit('parentUpdated', this.details.parent_id);
                        }).catch(error => this.$bvModal.msgBoxOk('Link to Parent operation failed.  Please try again later.'));
                }

            },
            unlinkCust()
            {
                this.$bvModal.msgBoxConfirm('Please confirm you want to remove this site as a linked site.', {
                        title: 'Are You Sure?',
                        size: 'md',
                        okVariant: 'danger',
                        okTitle: 'Yes',
                        cancelTitle: 'No',
                        centered: true,
                    }).then(res => {
                        if(res)
                        {
                            this.submitted = true;
                            axios.get(this.route('customer.removeParent', this.cust_details.cust_id))
                                .then(res => {
                                    this.$refs['details-edit-modal'].hide();
                                    this.details.parent_customer.cust_id = '';
                                    this.details.parent_customer.name = '';
                                    this.details.parent_id = null;
                                    this.submitted = false;
                                    this.init();
                                    this.$emit('parentUpdated', this.details.parent_id);
                                }).catch(error => this.$bvModal.msgBoxOk('Unlink to Parent operation failed.  Please try again later.'));
                        }
                    });
            },
            deactivateCust()
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
                        if(value)
                        {
                            axios.delete(this.route('customer.id.destroy', this.cust_details.cust_id))
                            .then(res => {
                                window.location.href = this.route('customer.index');
                            }).catch(error => this.$bvModal.msgBoxOk('Disable Customer operation failed.  Please try again later.'));
                        }
                    })
                    .catch(error => {
                        this.$bvModal.msgBoxOk('Disable Customer operation failed.  Please try again later.');
                    })
            }
        }
    }
</script>
