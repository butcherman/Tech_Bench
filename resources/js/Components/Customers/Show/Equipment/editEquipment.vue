<template>
    <b-button
        variant="warning"
        size="sm"
        pill
        @click="$refs['edit-equipment-modal'].show()"
    >
        <i class="fas fa-pencil-alt" />
        Edit
        <b-modal
            ref="edit-equipment-modal"
            title="Edit Equipment"
            hide-footer
            @show="populateForm"
        >
            <b-overlay :show="submitted">
                <template #overlay>
                    <form-loader />
                </template>
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form
                        @submit.prevent="handleSubmit(submitForm)"
                        novalidate
                    >
                        <h4 class="text-center">
                            {{equip.name}}
                        </h4>
                        <b-form-checkbox
                            v-if="customerStore.allowShare"
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
                            button_text="Update Equipment"
                            :submitted="submitted"
                        />
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
        props: {
            equipIndex: {
                type    : Number,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                form     : {},
            }
        },
        computed: {
            ...mapStores(useCustomerStore),
            equip()
            {
                return this.customerStore.equipment[this.equipIndex];
            }
        },
        methods: {
            populateForm()
            {
                let equipData = [];
                this.equip.customer_equipment_data.forEach(item => {
                    equipData.push({
                        field_name: item.field_name,
                        id        : item.id,
                        value     : item.value,
                    });
                });

                this.form = this.$inertia.form({
                    cust_id   : this.customerStore.cust_id,
                    shared    : this.equip.shared,
                    equip_id  : this.equip.equip_id,
                    equip_data: equipData,
                });
            },
            submitForm()
            {
                this.submitted = true;
                this.form.put(route('customers.equipment.update', this.equip.cust_equip_id), {
                    only    : ['equipment', 'flash', 'errors'],
                    onError : (error) => this.eventHub.$emit('validation-error', error),
                    onFinish: ()      => {
                        this.$refs['edit-equipment-modal'].hide();
                        this.submitted = false;
                    }
                })
            }
        },
    }
</script>
