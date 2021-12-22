<template>
    <b-button pill variant="warning" size="sm" @click="$refs['edit-equipment-modal'].show()">
            <i class="fas fa-pencil-alt"></i>
            Edit
            <b-modal
                ref="edit-equipment-modal"
                title="Edit Equipment"
                hide-footer
                @show="getEquipData"
            >
                <b-overlay :show="loading">
                    <template #overlay>
                        <form-loader></form-loader>
                    </template>
                    <ValidationObserver v-slot="{handleSubmit}">
                        <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                            <h4 class="text-center">{{equip.name}}</h4>
                            <b-form-checkbox v-if="allow_share" v-model="form.shared" class="text-center" switch>Share Equipment Across All Sites</b-form-checkbox>
                            <text-input v-for="(d, index) in form.data" :key="index" :label="d.field_name" v-model="form.data[index].value"></text-input>
                            <submit-button button_text="Update Equipment" :submitted="submitted"></submit-button>
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
            equip: {
                type:     Object,
                required: true,
            },
            allow_share: {
                type:     Boolean,
                default:  false,
            }
        },
        data() {
            return {
                loading:   false,
                submitted: false,
                form: {
                    cust_id:  this.cust_id,
                    shared:   this.equip.shared,
                    equip_id: this.equip.equip_id,
                    data:     [],
                }
            }
        },
        methods: {
            /**
             * Submit the Edit Equipment form
             */
            submitForm()
            {
                this.submitted = true;
                this.loading   = true;
                this.$inertia.put(route('customers.equipment.update', this.equip.cust_equip_id), this.form, {onFinish: () => {
                    this.$refs['edit-equipment-modal'].hide();
                    this.loading   = false;
                    this.submitted = false;
                }});
            },
            /**
             * Push all equipment data to the form so it is not reactive and update the existing page value
             */
            getEquipData()
            {
                console.log('triggered');
                this.form.data = [];
                this.equip.customer_equipment_data.forEach(el => {
                    this.form.data.push({
                        field_name: el.field_name,
                        id:         el.id,
                        value:      el.value,
                    });
                });
            }
        },
    }
</script>
