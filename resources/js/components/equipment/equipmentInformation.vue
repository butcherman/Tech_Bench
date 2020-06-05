<template>
    <b-overlay :show="showOverlay">
        <template v-slot:overlay>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Processing</h4>
        </template>
        <vue-good-table
            ref="equipment-fields-table"
            :columns="columns"
            :rows="rows"
            :sort-options="{enabled:true}"
            styleClass="vgt-table striped bordered"
        >
            <div slot="table-actions">
                <b-button pill size="sm" variant="warning" @click="newField">Create New Field</b-button>
            </div>
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'protected'">
                    <span v-if="!data.row.hidden">
                        <b-badge variant="warning" title="This field will always show without any masking" v-b-tooltip.hover>No</b-badge>
                    </span>
                    <span v-else>
                        <b-badge variant="info" title="This field will not show unless clicked or hovered over" v-b-tooltip.hover>Yes</b-badge>
                    </span>
                </span>
                <span v-else-if="data.column.field == 'usedBy'">
                    <span v-if="data.row.system_data_fields.length == 0">
                        <b-badge variant="warning">No</b-badge>
                    </span>
                    <span v-else>
                        <b-badge variant="info" class="pointer" title="Click For A List of Equipment That Uses This Field" @click="viewEquipList(data.row.system_data_fields)" v-b-tooltip.hover>Yes</b-badge>
                    </span>
                </span>
                <span v-else-if="data.column.field == 'actions'">
                    <i class="fas fa-pencil-alt pointer" title="Edit" v-b-tooltip.hover @click="editField(data.row)"></i>
                    <i class="fas fa-trash-alt pointer text-danger" title="Delete Field" v-if="data.row.system_data_fields.length == 0" v-b-tooltip.hover @click="deleteField(data.row.data_type_id)"></i>
                </span>
            </template>
        </vue-good-table>
        <b-modal ref="equipemnt-list-modal" title="Equipment List That Uses This Field" centered ok-only>
            <ul class="list-group">
                <li class="list-group-item" v-for="equip in equipList" :key="equip.field_id">{{equip.system_types.name}}</li>
            </ul>
        </b-modal>
        <b-modal ref="edit-field-modal" title="Equipment Fields" centered hide-footer>
            <b-form ref="edit-field-form" @submit="validateForm" novalidate :validated="validated">
                <b-form-group label="Field Name:" label-for="name">
                    <b-form-input
                        id="name"
                        type="text"
                        v-model="form.name"
                        required
                    ></b-form-input>
                    <b-form-invalid-feedback>This field is required</b-form-invalid-feedback>
                </b-form-group>
                <b-form-checkbox
                    v-model="form.hidden"
                    switch
                >
                    Protected Field
                </b-form-checkbox>
                <form-submit
                    class="mt-2"
                    button_text="Submit"
                    :submitted="submitted"
                ></form-submit>
            </b-form>
        </b-modal>
    </b-overlay>
</template>

<script>
    export default {
        props: {
            data: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                showOverlay: false,
                columns: [
                    {
                        label:   'Name',
                        field:   'name',
                        sortable: true,
                        filterOptions: {
                            enabled: true,
                        },
                    },
                    {
                        label: 'Used by Equipment',
                        field: 'usedBy',
                    },
                    {
                        label:   'Protected',
                        field:   'protected',
                        sortable: false,
                    },
                    {
                        label:   'Actions',
                        field:   'actions',
                        sortable: false,
                    }
                ],
                rows: this.data,
                equipList: [],
                submitted: false,
                validated: false,
                form: {
                    name:         null,
                    data_type_id: null,
                    hidden:       false,
                }
            }
        },
        methods: {
            viewEquipList(row)
            {
                this.$refs['equipemnt-list-modal'].show();
                this.equipList = row;
            },
            newField()
            {
                this.form.name         = null,
                this.form.data_type_id = null,
                this.$refs['edit-field-modal'].show();
            },
            editField(row)
            {
                this.form = row;
                this.$refs['edit-field-modal'].show();
            },
            deleteField(id)
            {
                this.$bvModal.msgBoxConfirm('Please confirm that you want to delete this field', {
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
                        this.showOverlay = true;
                        axios.delete(this.route('admin.equipment.delete_field', id))
                            .then(res => {
                                location.reload();
                            }).catch(error => this.eventHub.$emit('axiosError', error));
                    }
                })
                .catch(error => {
                    this.eventHub.$emit('axiosError', error);
                });
            },
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['edit-field-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                     this.submitted = true;
                     if(this.form.data_type_id)
                     {
                         axios.put(this.route('admin.equipment.submit_field'), this.form)
                            .then(res => {
                                location.reload();
                            }).catch(error => this.eventHub.$emit('axiosError', error));
                     }
                     else
                     {
                         axios.post(this.route('admin.equipment.new_field'), this.form)
                            .then(res => {
                                location.reload();
                            }).catch(error => this.eventHub.$emit('axiosError', error));
                     }
                }
            }
        }
    }
</script>
