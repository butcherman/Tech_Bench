<template>
    <div>
        <b-button pill variant="warning" size="sm" @click="$refs['edit-equipment-modal'].show();">
            <i class="fas fa-pencil-alt"></i>
            Edit
            <b-modal
                ref="edit-equipment-modal"
                title="Edit Equipment"
                hide-footer
            >
            <b-overlay :show="loading">
                <template #overlay>
                    <form-loader></form-loader>
                </template>
               <ValidationObserver v-slot="{handleSubmit}">
                    <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                        <h4 class="text-center">{{name}}</h4>
                        <b-form-checkbox v-model="form.shared" class="text-center" switch>Share Equipment Across All Sites</b-form-checkbox>
                        <text-input v-for="(d, index) in form.data" :key="index" :label="d.field_name" v-model="form.data[index].value"></text-input>
                        <submit-button button_text="Update Equipment" :submitted="submitted"></submit-button>
                    </b-form>
                    <b-button v-if="can_delete" variant="danger" class="mt-4" block @click="deleteEquip">Delete Equipment</b-button>
                </ValidationObserver>
            </b-overlay>
            </b-modal>
        </b-button>
    </div>
</template>

<script>
    export default {
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            data: {
                type:     Array,
                required: true,
            },
            name: {
                type:     String,
                required: true,
            },
            equip_id: {
                type:     Number,
                required: true,
            },
            cust_equip_id: {
                type:     Number,
                required: true,
            },
            shared: {
                type:     Boolean,
                required: true,
            },
            can_delete: {
                type:     Boolean,
                required: true,
            }
        },
        data() {
            return {
                loading:   false,
                submitted: false,
                form: {
                    cust_id:  this.cust_id,
                    equip_id: this.equip_id,
                    shared:   this.shared,
                    data:     this.data,
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
            submitForm()
            {
                this.submitted = true;
                this.loading   = true;
                this.$inertia.put(route('customers.equipment.update', this.cust_equip_id), this.form, {onFinish: () => {
                    this.$refs['edit-equipment-modal'].hide();
                    this.loading   = false;
                    this.submitted = false;
                    this.$emit('completed');
                }});
            },
            deleteEquip()
            {
                this.$bvModal.msgBoxConfirm('All information for this equipment will also be deleted',
                    {
                        title:          'Are you sure?',
                        size:           'sm',
                        buttonSize:     'sm',
                        okVariant:      'danger',
                        okTitle:        'YES',
                        cancelTitle:    'NO',
                        footerClass:    'p-2',
                        hideHeaderClose: false,
                        centered:        true
                    }).then(value => {
                        if(value)
                        {
                            this.loading = true;
                            axios.delete(this.route('customers.equipment.destroy', this.cust_equip_id))
                                .then(res => {
                                    this.$refs['edit-equipment-modal'].hide();
                                    this.loading   = false;
                                    this.submitted = false;
                                    this.$emit('completed');
                                }).catch(error => this.eventHub.$emit('axiosError', error));
                        }
                    });
            }
        },
    }
</script>
