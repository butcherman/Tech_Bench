<template>
    <b-button class="float-right" pill variant="primary" size="sm" v-b-modal.new-equipment-modal>
        <i class="fas fa-plus"></i>
        New
        <b-modal
            id="new-equipment-modal"
            ref="new-equipment-modal"
            title="Add New Equipment"
            hide-footer
            @show="getEquipList"
            @hidden="resetForm"
        >
            <b-overlay :show="loading">
                <template #overlay>
                    <form-loader></form-loader>
                </template>
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                        <dropdown-input v-model="form.equip_id" rules="required" label="Select Equipment Type" @change="populateForm">
                            <option :value="null">Select An Equipment Type</option>
                            <optgroup v-for="cat in equipList" :key="cat.cat_id" :label="cat.name">
                                <option v-for="equip in cat.equipment_type" :key="equip.equip_id" :value="equip.equip_id" :disabled="existing_equip.includes(equip.equip_id)">{{equip.name}}</option>
                            </optgroup>
                        </dropdown-input>
                        <b-form-checkbox v-model="form.shared" class="text-center" switch>Share Equipment Across All Sites</b-form-checkbox>
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
            existing_equip: {
                type:     Array,
                required: false,
            },
            cust_id: {
                type:     Number,
                required: true,
            }
        },
        data() {
            return {
                equipList: [],
                loading: true,
                submitted: false,
                form: {
                    cust_id:  this.cust_id,
                    equip_id: null,
                    shared:   false,
                    data:     [],
                },
            }
        },
        computed: {
            errors()
            {
                return this.$page.props.errors;
            }
        },
        methods: {
            getEquipList()
            {
                if(this.equipList.length == 0)
                {
                    axios.get(this.route('customers.equip-list'))
                        .then(res => {
                            this.equipList = res.data;
                            this.loading   = false;
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            },
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
            submitForm()
            {
                this.submitted = true;
                this.loading   = true;
                this.$inertia.post(route('customers.equipment.store'), this.form, {onFinish: () => {
                    this.submitted = false;
                    this.loading   = false;
                    this.resetForm();
                    this.$refs['new-equipment-modal'].hide();
                    this.$emit('completed');
                }});
            },
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
