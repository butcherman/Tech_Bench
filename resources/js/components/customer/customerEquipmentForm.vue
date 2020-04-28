<template>
    <b-modal :title="modalTitle" ref="equipmentModal" hide-footer @close="resetModal">
        <div v-if="error">
            <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Something bad happened...</h5>
        </div>
        <div v-else-if="loading">
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Loading Form</h4>
        </div>
        <b-overlay v-else :show="submitted">
            <template v-slot:overlay>
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
                <h4 class="text-center">Processing...</h4>
            </template>
            <b-form @submit="validateForm" ref="equipmentForm" :validated="validated" novalidate>
                <b-form-group label="Equipment Type" label-size="lg" v-if="newEquip" >
                    <b-form-select v-model="form.equip" @change="populateDataFields" required>
                        <option :value="null">Please Select An Equipment Type</option>
                        <optgroup v-for="cat in equipList" :key="cat.cat_id" :label="cat.name">
                            <option v-for="sys in cat.system_types" :key="sys.sys_id" :value="sys">{{sys.name}}</option>
                        </optgroup>
                    </b-form-select>
                    <b-form-invalid-feedback>You must select an equipment type</b-form-invalid-feedback>
                </b-form-group>
                <b-form-checkbox v-model="form.share" switch v-if="newEquip">Equipment is Shared Across All Sites</b-form-checkbox>
                <b-form-group label="Equipment Information" label-size="lg">
                    <b-form-group v-for="data in form.fields" :key="data.field_id" :label="data.data_field_name" :label-for="'sys-data-'+data.field_id">
                        <b-form-input :id="'sys-data-'+data.field_id" v-model="data.value"></b-form-input>
                    </b-form-group>
                </b-form-group>
                <form-submit
                    class="mt-3"
                    :button_text="btnText"
                    :submitted="submitted"
                ></form-submit>
                <b-button block variant="danger" class="mt-4" @click="deleteEquipment">Delete This Equipment</b-button>
            </b-form>
        </b-overlay>
    </b-modal>
</template>

<script>
    export default {
        props: {
            cust_id: {
                type: Number,
                required: true,
            },
            existing_equip: {
                type: Array,
                default: [],
                required: false,
            }
        },
        data() {
            return {
                error: false,
                loading: false,
                submitted: false,
                validated: false,
                equipList: [],
                newEquip: true,
                form: {
                    cust_id: this.cust_id,
                    equip: null,
                    share: false,
                    fields: [],
                },
            }
        },
        computed: {
            modalTitle()
            {
                return this.newEquip ? 'Add New Equipment' : 'Edit '+this.form.equip.name;
            },
            btnText()
            {
                return this.newEquip ? 'Add New Equipment' : 'Update '+this.form.equip.name;
            }
        },
        methods: {
            //  Initialize the modal to assign new equipment
            initNewEquip()
            {
                this.newEquip = true;
                this.form.equip = null;
                this.form.fields = [];
                this.getEquipmentList();
                this.$refs['equipmentModal'].show();
            },
            //  Initialize the modal to edit existing equipment
            initEditEquip(data)
            {
                this.newEquip = false,
                this.form.equip = {};
                this.form.equip.name = data.sys_name;
                this.form.equip.sys_id   = data.sys_id;
                this.form.fields = data.customer_system_data;
                this.$refs['equipmentModal'].show();
            },
            //  Reset the modal
            resetModal()
            {
                this.submitted    = false;
                this.validated    = false;
                this.equipList    = [];
                this.newEquip     = true;
                this.form.cust_id = this.cust_id,
                this.form.equip   = null;
                this.form.fields  = [];
            },
            //  Delete the selected equipment
            deleteEquipment()
            {
                this.$bvModal.msgBoxConfirm('Please confirm you want to delete '+this.form.equip.name+' from this customer.', {
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
                        axios.delete(this.route('customer.delEquip', [this.form.fields[0].cust_sys_id, this.cust_id]))
                            .then(res => {
                                if(!res.data.success)
                                {
                                    this.$bvModal.msgBoxOk('Unable to delete this equipment.  If this equipment belongs to multiple sites, it can only be deleted from the parent site.', {
                                        title: 'Error',
                                        size: 'md',
                                        centered: true,
                                    });
                                }
                                else
                                {
                                    this.$emit('completed');
                                }

                                this.$refs['equipmentModal'].hide();
                                this.submitted = false;
                            }).catch(error => this.error = true);
                    }
                });
            },
            //  Get a list of all equipment types that can be assigned to a customer
            getEquipmentList()
            {
                if(!this.equipList.length)
                {
                    this.loading = true;
                    axios.get(this.route('customer.systems.index'))
                    .then(res => {
                        this.equipList = res.data;
                        this.loading = false;
                    }).catch(error => this.error = true);
                }
            },
            //  Finish populating the form with the necessary information that is supposed to be gathered
            populateDataFields()
            {
                //  If user is nulling out the selected system, null out form
                if(this.form.equip == null)
                {
                    this.form.fields = null;
                }
                //  Be sure that the user is not trying to select a system the customer already has
                else if(this.existing_equip.includes(this.form.equip.sys_id))
                {
                    this.$bvModal.msgBoxOk('Customer already has this equipment.', {
                        title: 'Error',
                        size: 'md',
                        centered: true,
                    }).then(res => {this.form.equip = null; this.form.fields = null;});
                }
                else
                {
                    this.form.fields = this.form.equip.system_data_fields;
                }
            },
            validateForm(e)
            {
                e.preventDefault();
                console.log(this.form);
                if(this.$refs.equipmentForm.checkValidity() === false)
                {
                    this.validated = true;
                }
                else
                {
                    this.submitted = true;

                    if(this.newEquip)
                    {
                        axios.post(this.route('customer.systems.store'), this.form)
                            .then(res => {
                                this.$emit('completed');
                                this.$refs['equipmentModal'].hide();
                                this.submitted = false;
                            }).catch(error => this.$bvModal.msgBoxOk('Something bad happened.  Please try again later.'));
                    }
                    else
                    {
                        axios.put(this.route('customer.systems.update', this.cust_id), this.form)
                            .then(res => {
                                this.$emit('completed');
                                this.$refs['equipmentModal'].hide();
                                this.submitted = false;
                            }).catch(error => this.error = true);
                    }
                }
            }
        }
    }
</script>
