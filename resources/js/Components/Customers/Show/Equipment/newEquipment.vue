<template>
    <b-button
        size="sm"
        variant="primary"
        class="float-right"
        pill
        v-b-modal.new-equipment-modal
    >
        <i class="fas fa-plus" />
        New
        <b-modal
            id="new-equipment-modal"
            title="Add Equipment"
            ref="new-equipment-modal"
            hide-footer
            @show="getEquipmentList"
        >
            <b-overlay :show="loading || submitted">
                <template #overlay>
                    <form-loader v-if="submitted" />
                    <atom-loader v-else />
                </template>
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form
                        @submit.prevent="handleSubmit(submitForm)"
                        novalidate
                    >
                        <dropdown-input
                            v-model="form.equip_id"
                            rules="required"
                            label="Select Equipment Type"
                            @change="populateForm"
                        >
                            <option :value="null">
                                Select An Equipment Type
                            </option>
                            <optgroup
                                v-for="cat in equipList"
                                :key="cat.cat_id"
                                :label="cat.name"
                            >
                                <option
                                    v-for="equip in cat.equipment_type"
                                    :key="equip.equip_id"
                                    :value="equip.equip_id"
                                    :disabled="existing_equip.includes(equip.equip_id)"
                                >
                                    {{equip.name}}
                                </option>
                            </optgroup>
                        </dropdown-input>
                        <b-form-checkbox
                            v-show="customerStore.allowShare"
                            v-model="form.shared"
                            class="text-center"
                            switch
                        >
                            Share Equipment Across All Sites
                        </b-form-checkbox>
                        <text-input
                            v-for="(data, index) in form.equip_data"
                            v-model="form.equip_data[index].value"
                            :key="index"
                            :label="data.field_name"
                        />
                        <submit-button
                            button_text="Add Equipment"
                            :submitted="submitted"
                        ></submit-button>
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
                loading  : false,
                submitted: false,
                equipList: [],
                form     : this.$inertia.form({
                    cust_id   : null,
                    equip_id  : null,
                    shared    : false,
                    equip_data: [],
                }),
            }
        },
        computed: {
            ...mapStores(useCustomerStore),
            existing_equip()
            {
                let equipIdArr = [];
                this.customerStore.equipment.forEach(item => equipIdArr.push(item.equip_id));

                return equipIdArr;
            }
        },
        methods: {
            getEquipmentList()
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
            populateForm(equipId)
            {
                this.form.equip_data = [];
                this.equipList.forEach(cat => {
                    let equip = cat.equipment_type.find(e => e.equip_id === equipId);

                    if(equip)
                    {
                        equip.data_field_type.forEach(field => {
                            this.form.equip_data.push({
                                value     : '',
                                field_name: field.name,
                                type_id   : field.type_id,
                            });
                        });
                    }
                });
            },
            submitForm()
            {
                this.submitted    = true;
                this.form.cust_id = this.customerStore.cust_id;

                this.form.post(route('customers.equipment.store'), {
                    only     : ['equipment', 'flash', 'errors'],
                    onSuccess: ()      => {
                        this.$refs['new-equipment-modal'].hide()
                        this.form.reset();
                    },
                    onError  : (error) => this.eventHub.$emit('validation-error', error),
                    onFinish : ()      => this.submitted = false,
                });
            }
        },
    }
</script>
