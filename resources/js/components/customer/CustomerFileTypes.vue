<template>
    <div>
        <div v-if="error">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-5">
                    <img src="/img/err_img/sry_error.png" alt="Error" id="header-logo" />
                    <!-- TODO - Replace this with head-scratch image -->
                </div>
                <div class="col-md-5">
                    <h3 class="text-danger">Something Bad Happened:</h3>
                    <p>
                        Sorry about that.
                    </p>
                    <p>
                        Our minions are busy at work to determine what happened.
                    </p>
                </div>
            </div>
        </div>
        <vue-good-table
            v-else
            mode="remote"
            ref="fileTypesTable"
            styleClass="vgt-table bordered w-100"
            :columns="table.columns"
            :rows="table.rows"
            :sort-options="{enabled:true}"
            :isLoading.sync="isLoading"
        >
            <div slot="emptystate">
                <h4 v-if="loadDone" class="text-center">No File Types Available</h4>
                <atom-spinner v-else
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
            </div>
            <div slot="table-actions">
                <b-button variant="primary" size="sm" pill @click="newType">New File Type</b-button>
            </div>
            <div slot="loadingContent">
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
            </div>
            <template slot="table-row" slot-scope="data">

                <span v-if="data.column.field == 'actions'">
                    <i class="fas fa-edit pl-2 text-muted pointer" title="Edit File Type Name" v-b-tooltip.hover @click="editType(data.row)"></i>
                    <i class="fas fa-trash-alt pl-2 text-muted pointer" title="Delete File Type" v-b-tooltip.hover @click="confirmDelete(data.row)"></i>
                </span>
            </template>
        </vue-good-table>
        <b-modal id="edit-type-modal" title="Edit File Type" ref="fileTypeModal" hide-footer>
            <b-form @submit="validateForm" :validated="validated" novalidate ref="fileTypeForm">
                <b-form-group label="File Type Name:" label-for="name">
                    <b-form-input
                        id="name"
                        type="text"
                        v-model="form.name"
                        required
                    ></b-form-input>
                    <b-form-invalid-feedback>You must enter a name</b-form-invalid-feedback>
                </b-form-group>
                <form-submit
                    :button_text="btnText"
                    :submitted="submitted"
                ></form-submit>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: [
            //
        ],
        data() {
            return {
                error: false,
                loadDone: false,
                isLoading: false,
                table: {
                    columns: [
                        {
                            label: 'Type Name',
                            field: 'text',
                            sortable: true,
                        },
                        {
                            label: 'Actions',
                            field: 'actions',
                            sortable: false,
                        }
                    ],
                    rows: [],
                },
                fileTypes: [],
                form: {
                    name: null,
                    id: null,
                },
                submitted: false,
                validated: false,
                btnText: '',
            }
        },
        created() {
            this.getFileTypes();
        },
        methods: {
            //  Pull all file types
            getFileTypes()
            {
                this.loading = true;
                axios.get(this.route('admin.getCustFileTypes'))
                    .then(res => {
                        this.table.rows = res.data;
                        this.loadDone = true;
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            //  Open form to edit an existing file type
            editType(data)
            {
                this.form.name = data.text;
                this.form.id = data.value;
                this.btnText = 'Update File Type Name';
                this.$refs.fileTypeModal.show();

            },
            //  validate the form
            validateForm(e)
            {
                e.preventDefault();

                if(this.$refs.fileTypeForm.checkValidity() == false)
                {
                    this.validated = true;
                }
                else
                {
                    this.submitted = true;
                    if(this.form.id)
                    {
                        this.submitEdit();
                    }
                    else
                    {
                        this.submitNew();
                    }
                }
            },
            resetForm()
            {
                this.$refs.fileTypeModal.hide();
                this.form.name = null;
                this.form.id = null;
                this.submitted = false;
                this.validated = false;
                this.getFileTypes();
            },
            submitEdit()
            {
                axios.put(this.route('admin.setCustFileTypes'), this.form)
                        .then(res => {
                            this.resetForm();
                        }).catch(error => this.$bvModal.msgBoxOk('Update operation failed.  Please try again later.'));
            },
            newType()
            {
                this.btnText = 'Create New File Type';
                this.$refs.fileTypeModal.show();
            },
            submitNew()
            {
                axios.post(this.route('admin.submitNewFileType'), this.form)
                    .then(res => {
                        this.resetForm();
                    }).catch(error => this.$bvModal.msgBoxOk('Update operation failed.  Please try again later.'));
            },
            confirmDelete(data)
            {
                this.$bvModal.msgBoxConfirm('This cannot be undone.', {
                    title: 'Are You Sure?',
                    size: 'md',
                    okVariant: 'danger',
                    okTitle: 'Yes',
                    cancelTitle: 'No',
                    centered: true,
                })
                .then(res => {
                    if(res)
                    {
                        this.isLoading = true;
                        axios.delete(this.route('admin.delCustFileType', data.value))
                            .then(res => {
                                if(res.data.success == false && res.data.reason === 'In Use')
                                {
                                    this.$bvModal.msgBoxOk('This File Type is being used by some customers.  Unalbe to delete at this time');
                                }
                                this.resetForm();
                            }).catch(error => this.$bvModal.msgBoxOk('Delete operation failed.  Please try again later.'));
                    }
                });
            },
        }
    }
</script>
