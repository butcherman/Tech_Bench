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
                                        <text-input v-model="form.cust_id" name="cust_id" placeholder="Enter Customer ID Number" rules="numeric|unique-customer" mode="lazy" :errors="errors">
                                            <template #label>
                                                Customer ID:
                                                <i class="far fa-question-circle pointer text-warning" title="Click for More Information" v-b-tooltip:hover @click="$bvToast.show('cust-id-toast')"></i>
                                            </template>
                                        </text-input>
                                    </b-col>
                                    <b-col md="6">
                                        <ValidationProvider v-slot="v" mode="lazy">
                                            <b-form-group label-for="parent_name">
                                                <template slot="label">
                                                    Parent Site:
                                                    <i class="far fa-question-circle pointer text-warning" title="Click for More Information" v-b-tooltip:hover @click="$bvToast.show('parent-id-toast')"></i>
                                                </template>
                                                <b-input-group>
                                                    <b-form-input
                                                        id="parent_name"
                                                        name="parent_name"
                                                        type="text"
                                                        placeholder="(Optional)"
                                                        v-model="form.parent_name"
                                                        :state="parentState"
                                                        @blur="checkParent"
                                                    ></b-form-input>
                                                    <b-input-group-append>
                                                        <b-button varient="primary" @click="checkParent"><span class="fas fa-search"></span></b-button>
                                                    </b-input-group-append>
                                                </b-input-group>
                                                <b-form-invalid-feedback :state="false">{{v.errors[0]}}</b-form-invalid-feedback>
                                            </b-form-group>
                                        </ValidationProvider>
                                    </b-col>
                                </b-form-row>
                                <text-input v-model="form.name" label="Customer Name" name="name" placeholder="Enter Customer Name" rules="required" :errors="errors"></text-input>
                                <text-input v-model="form.dba_name" label="DBA Name" name="dba_name" placeholder="Customer secondary name/AKA name" :errors="errors"></text-input>
                                <text-input v-model="form.address" label="Customer Address" name="address" rules="required" :errors="errors"></text-input>
                                <text-input v-model="form.city" label="City" name="city" rules="required" :errors="errors"></text-input>
                                <b-form-row>
                                    <b-col md="6">
                                        <all-states v-model="form.state"></all-states>
                                    </b-col>
                                    <b-col md="6">
                                        <text-input v-model="form.zip" label="Zip Code" name="zip" rules="required|numeric" :errors="errors"></text-input>
                                    </b-col>
                                </b-form-row>
                                <submit-button button_text="Create Customer" :submitted="submitted"></submit-button>
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
        <b-toast id="parent-id-toast" title="Instructions for Parent Site" variant="info">
            <p class="my-4 text-center">If this is part of a Multi-Site customer that needs to include its own information, or share information with another site, enter the name of the priamry customer site.</p>
        </b-toast>
        <quick-search ref="quick-search" @selected-customer="selectedParent"></quick-search>
    </div>
</template>

<script>
    import App from '../../Layouts/app';
    export default {
        layout: App,
        props: {
            errors: {
                type:     Object,
                required: false,
            }
        },
        data() {
            return {
                form: {
                    cust_id:     '',
                    parent_id:   '',
                    parent_name: '',
                    name:        '',
                    dba_name:    '',
                    address:     '',
                    city:        '',
                    state:       '',
                    zip:         '',
                },
                parentState: null,
                submitted:   false,
                parentList:  [],
            }
        },
        methods: {
            submitForm()
            {
                this.$inertia.post(route('customers.store'), this.form);
            },
            checkParent(e)
            {
                if(this.form.parent_name === null || this.form.parent_name === '' && e.type !== 'click')
                {
                    this.form.parent_id = null;
                }
                else
                {
                    this.$refs['quick-search'].open(this.form.parent_name);
                }
            },
            selectedParent(parent)
            {
                this.form.parent_name = parent.name;
                this.form.parent_id   = parent.cust_id;
            }
        }
    }
</script>
