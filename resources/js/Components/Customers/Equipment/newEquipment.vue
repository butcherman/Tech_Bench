<template>
    <b-button class="float-right" pill variant="primary" size="sm" v-b-modal.new-equipment-modal>
        <i class="fas fa-plus"></i>
        Add
        <b-modal
            id="new-equipment-modal"
            ref="new-equipment-modal"
            title="Add Equipment"
            hide-footer
            @show="getEquipList"
            @hidden="resetForm"
        >
            <b-overlay :show="loading">
                <template #overlay>
                    <form-loader v-if="submitted"></form-loader>
                    <atom-loader v-else></atom-loader>
                </template>
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                        <dropdown-input v-model="form.equip_id" rules="required" label="Select Equipment Type" @change="populateForm">
                            <option :value="null">Select An Equipment Type</option>
                            <optgroup v-for="cat in equipList" :key="cat.cat_id" :label="cat.name">
                                <option v-for="equip in cat.equipment_type" :key="equip.equip_id" :value="equip.equip_id" :disabled="existing_equip.includes(equip.equip_id)">{{equip.name}}</option>
                            </optgroup>
                        </dropdown-input>
                        <b-form-checkbox v-show="allow_share" v-model="form.shared" class="text-center" switch>Share Equipment Across All Sites</b-form-checkbox>
                        <text-input v-for="(d, index) in form.data" :key="index" :label="d.name" v-model="form.data[index].value"></text-input>
                        <submit-button button_text="Add Equipment" :submitted="submitted"></submit-button>
                    </b-form>
                </ValidationObserver>
            </b-overlay>
        </b-modal>
    </b-button>
</template>

<script>
    export default {
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            existing_equip: {
                type:     Array,
                required: false,
            },
            allow_share: {
                type:    Boolean,
                default: false,
            }
        },
        data() {
            return {
                equipList: [],
                loading:   true,
                submitted: false,
                form: {
                    cust_id:  this.cust_id,
                    equip_id: null,
                    shared:   false,
                    data:     [],
                },
            }
        },
        methods: {
            /**
             * Submit the new Equipment form
             */
            submitForm()
            {
                this.submitted = true;
                this.loading   = true;
                this.$inertia.post(route('customers.equipment.store'), this.form, {onFinish: () => {
                    this.submitted = false;
                    this.loading   = false;
                    this.resetForm();
                    this.$refs['new-equipment-modal'].hide();
                }});
            },
            /**
             * Get the list of equipment available to assign to this customer
             */
            getEquipList()
            {
                if(this.equipList.length == 0)
                {
                    this.loading = true;
                    axios.get(this.route('list-equipment'))
                        .then(res => {
                            this.equipList = res.data;
                            this.loading   = false;
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            },
            /**
             * Populate the form with the available data fields for the equipment type
             */
            populateForm(equip_id)
            {
                this.equipList.forEach(cat => {
                    var equip = cat.equipment_type.filter(e => e.equip_id === equip_id);
                    if(equip.length == 1)
                    {
                        this.form.data = equip[0].data_field_type;
                    }
                });
            },
            /**
             * Reset the form to its default value so a new equipment type can be added
             */
            resetForm()
            {
                this.form = {
                    cust_id:  this.cust_id,
                    equip_id: null,
                    shared:   false,
                    data:     [],
                }
            }
        },
    }
</script>
