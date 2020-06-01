<template>
    <b-overlay :show="submitted">
        <template v-slot:overlay>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Processing</h4>
        </template>
        <b-form @submit="validateForm" novalidate :validated="validated" ref="equipment-form">
            <b-form-group label="Equipment Category:" label-for="cat">
                <b-form-select
                    v-if="cat_info"
                    v-model="form.cat_id"
                    value-field="cat_id"
                    text-field="name"
                    :options="cat_info"
                    required
                    placeholder="Select A Category"
                ></b-form-select>
            <b-form-invalid-feedback>
                    <div v-for="msg in valid.msg.cat_id" :key="msg">
                        {{msg}}
                    </div>
                </b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="Equipment Name:" label-for="name">
                <b-form-input
                    id="name"
                    type="text"
                    v-model="form.name"
                    :state="valid.state.name"
                    @blur="validateName"
                    required
                ></b-form-input>
                <b-form-invalid-feedback>
                    <div v-for="msg in valid.msg.name" :key="msg">
                        {{msg}}
                    </div>
                </b-form-invalid-feedback>
            </b-form-group>
            <fieldset>
                <label>Customer Information to Gather</label>
                <draggable animation="200" :list="form.system_data_fields">
                    <div v-for="(obj, index) in form.system_data_fields" :key="index" class="mb-2">
                        <b-input-group v-if="obj.field_id">
                            <b-form-input
                                type="text"
                                :value="obj.data_field_name"
                                disabled
                            >
                            </b-form-input>
                            <template v-slot:append>
                                <i class="fas fa-sort pointer ml-2 mr-1" title="Drag to Re-Sort" v-b-tooltip.hover></i>
                                <i class="fas fa-times-circle text-danger pointer" title="Delete This Item" v-b-tooltip.hover @click="delField(index)"></i>
                            </template>
                        </b-input-group>
                        <b-input-group v-else>
                            <template>
                                <b-form-input list="my-list-id" autocomplete="false" v-model="form.system_data_fields[index]"></b-form-input>
                                <datalist id="my-list-id">
                                    <option v-for="field in fields" :key="field.data_type_id">{{field.name}}</option>
                                </datalist>
                            </template>
                            <template v-slot:append>
                                <i class="fas fa-sort pointer ml-2 mr-1" title="Drag to Re-Sort" v-b-tooltip.hover></i>
                                <i class="fas fa-times-circle text-danger pointer" title="Delete This Item" v-b-tooltip.hover @click="delField(index)"></i>
                            </template>
                        </b-input-group>
                    </div>
                </draggable>
                <b-button size="sm" pill class="float-right" variant="info" @click="addRow">Add Row</b-button>
            </fieldset>
            <form-submit
                button_text="Submit Equipment Information"
                :submitted="submitted"
                class="mt-2"
            ></form-submit>
            <b-button variant="danger" block @click="deleteEquip" v-if="equip_info">Delete This Equipment</b-button>
        </b-form>
    </b-overlay>
</template>

<script>
    export default {
        props: {
            equip_info: {
                type:     Object,
                required: false,
            },
            fields: {
                type:     Array,
                required: true,
            },
            cat_info: {
                type:     Array,
                required: false,
            }
        },
        data() {
            return {
                submitted: false,
                validated: false,
                form: {
                    name:   null,
                    system_data_fields: [],
                },
                valid: {
                    state: {
                        name: null,
                    },
                    msg: {
                        name: ['Please Enter A Name For This Equipment'],
                        cat_id: ['You must select a Category for this Equipment'],
                    }
                }
            }
        },
        mounted() {
             if(this.equip_info)
             {
                 this.form   = this.equip_info;
             }
             else
             {
                 this.form.system_data_fields.push('');
             }
        },
        methods: {
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
                     if(this.equip_info)
                     {
                         axios.put(this.route('admin.equipment.types.update', this.equip_info.sys_id), this.form)
                         .then(res => {
                             location.href = this.route('admin.equipment.types.index');
                         }).catch(error => {
                            this.eventHub.$emit('axiosError', error);
                            this.form   = this.equip_info;
                        });
                     }
                     else
                     {
                         axios.post(this.route('admin.equipment.types.store'), this.form)
                             .then(res => {
                                 location.href = this.route('admin.equipment.types.index');
                             }).catch(error => this.eventHub.$emit('axiosError', error));
                     }
                }
            },
            deleteEquip()
            {
                this.$bvModal.msgBoxConfirm('Please confirm that you want to delete this equipment', {
                    title:          'THIS CANNOT BE UNDONE!!!',
                    size:           'sm',
                    buttonSize:     'sm',
                    okVariant:      'danger',
                    okTitle:        'YES',
                    cancelTitle:    'NO',
                    footerClass:    'p-2',
                    hideHeaderClose: false,
                    centered:        true
                })
                .then(value => {
                    if(value)
                    {
                        this.submitted = true;
                        axios.delete(this.route('admin.equipment.types.destroy', this.equip_info.sys_id))
                            .then(res => {
                                location.href = this.route('admin.equipment.types.index');
                            }).catch(error => {
                                this.eventHub.$emit('axiosError', error)
                                this.submitted = false;
                            });
                    }
                })
                .catch(error => {
                    this.eventHub.$emit('axiosError', error);
                });
            },
            delField(index)
            {
                this.form.system_data_fields.splice(index, 1);
            },
            addRow()
            {
                this.form.system_data_fields.push('');
            },
            validateName()
            {
                if(!this.form.name)
                {
                    this.valid.state.name = null;
                }
                else if(!this.form.name.match(/^[a-zA-Z0-9_ ]*$/g))
                {
                    this.valid.msg.name = ["No Special Characters Are Allowed"];
                    this.valid.state.name = false;
                }
                else
                {
                    this.valid.state.name = true;
                }
            }
        }
    }
</script>
