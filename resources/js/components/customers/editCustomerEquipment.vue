<template>
    <div>
        <b-button :variant="button_variant" pill size="sm" @click="openModal">
            <i class="fas" :class="button_class" aria-hidden="true"></i>
            {{button_msg}}
        </b-button>
        <b-modal :title="button_msg" ref="customer-equipment-modal" hide-footer>
            <div v-if="loading">
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
                <h4 class="text-center">Loading Form...</h4>
            </div>
            <b-overlay v-else :show="submitted">
                <template v-slot:overlay>
                    <atom-spinner
                        :animation-duration="1000"
                        :size="60"
                        color="#ff1d5e"
                        class="mx-auto"
                    />
                    <h4 class="text-center">Processing</h4>
                </template>
                <b-form @submit="validateForm" ref="equipment-form" novalidate :validated="validated">
                    <b-form-group label="Equipment Type" label-size="lg" :disabled="edit_equip" v-if="!edit_equip">
                        <b-form-select v-model="form.sys_id" @change="populateDataFields" required>
                            <option :value="null">Please Select An Equipment Type</option>
                            <optgroup v-for="cat in equipList" :key="cat.cat_id" :label="cat.name">
                                <option v-for="sys in cat.system_types" :key="sys.sys_id" :value="sys.sys_id">{{sys.name}}</option>
                            </optgroup>
                        </b-form-select>
                        <b-form-invalid-feedback>You must select an equipment type</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-checkbox v-model="form.shared" switch v-if="linked_site">Equipment is Shared Across All Sites</b-form-checkbox>
                    <b-form-group label="Equipment Information" label-size="lg" v-show="form.fields.length > 0" class="mt-2">
                        <b-form-group v-for="data in form.fields" :key="data.field_id" :label="data.data_field_name" :label-for="'sys-data-'+data.field_id">
                            <b-form-input :id="'sys-data-'+data.field_id" v-model="data.value"></b-form-input>
                        </b-form-group>
                    </b-form-group>
                    <form-submit
                        class="mt-3"
                        :button_text="button_msg"
                        :submitted="submitted"
                    ></form-submit>
                    <b-button block variant="danger" class="mt-4" @click="deleteEquipment">Delete This Equipment</b-button>
                </b-form>
            </b-overlay>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            linked_site: {
                type:     Boolean,
                required: false,
                default:  false,
            },
            equipment_data: {
                type:     Object,
                required: false,
                default:  null,
            }
        },
        data() {
            return {
                loading:   true,
                submitted: false,
                validated: false,
                equipList: [],
                form: {
                    sys_id:  null,
                    cust_id: this.cust_id,
                    shared:  false,
                    fields:  [],
                }
            }
        },
        computed: {
            button_class()
            {
                return this.equipment_data ? 'fa-pencil-alt' : 'fa-plus';
            },
            button_msg()
            {
                return this.equipment_data ? 'Edit '+this.equipment_data.sys_name : 'Add Equipment';
            },
            button_variant()
            {
                return this.equipment_data ? 'warning' : 'primary';
            },
            edit_equip()
            {
                return this.equipment_data ? true : false;
            }
        },
        methods: {
            openModal()
            {
                this.$refs['customer-equipment-modal'].show();
                if(!this.equipment_data)
                {
                    this.getEquipmentList();
                }
                else
                {
                    this.form.sys_id = this.equipment_data.sys_id;
                    this.form.shared = this.equipment_data.shared;
                    this.form.fields = this.equipment_data.customer_system_data;
                    this.loading     = false;
                }
            },
            getEquipmentList()
            {
                axios.get(this.route('customer.equipment.index'))
                    .then(res => {
                        this.equipList = res.data;
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            populateDataFields(sysID)
            {
                this.loading = true;
                axios.get(this.route('admin.equipment.get_fields', sysID))
                    .then(res => {
                        this.form.fields = res.data;
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['equipment-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                     this.submitted = true;
                     if(this.edit_equip)
                     {
                         axios.put(this.route('customer.equipment.update', this.equipment_data.cust_sys_id), this.form)
                             .then(res => {
                                 this.submitted = false;
                                 this.$emit('equipment-updated');
                                 this.$refs['customer-equipment-modal'].hide();
                             }).catch(error => this.eventHub.$emit('axiosError', error));
                     }
                     else
                     {
                         axios.post(this.route('customer.equipment.store'), this.form)
                             .then(res => {
                                 this.submitted = false;
                                 this.$emit('equipment-updated');
                                 this.$refs['customer-equipment-modal'].hide();
                             }).catch(error => this.eventHub.$emit('axiosError', error));
                     }
                }
            },
            deleteEquipment()
            {
                this.$bvModal.msgBoxConfirm('Please confirm you want to delete '+this.equipment_data.sys_name+' from this customer.', {
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
                        axios.delete(this.route('customer.equipment.destroy', this.equipment_data.cust_sys_id))
                            .then(res => {
                                this.$emit('equipment-updated');
                                this.$refs['customer-equipment-modal'].hide();
                            }).catch(error => this.error = true);
                    }
                });
            },
        }
    }
</script>
