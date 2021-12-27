<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Application Backups</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin">
                <div class="card">
                    <div class="card-body text-center">
                        <b-button variant="info" block @click="runBackup" :disabled="runningBackup">
                            <span class="spinner-border spinner-border-sm text-danger" v-show="runningBackup"></span>
                            {{buttonText}}
                        </b-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Automatic Backup Options</div>
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-overlay :show="submitted">
                                <template #overlay>
                                    <form-loader text="Processing..."></form-loader>
                                </template>
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <b-form-checkbox
                                        v-model="form.enabled"
                                        switch
                                    >
                                        Enable Nightly Backups
                                    </b-form-checkbox>
                                    <b-form-group label="Number of Backups to Keep:" label-for="number" v-show="form.enabled">
                                        <span class="small">{{form.number}}</span>
                                        <b-form-input
                                            id="number"
                                            type="range"
                                            v-model="form.number"
                                            min="1"
                                            max="365"
                                            :disabled="!form.enabled"
                                            required
                                        ></b-form-input>
                                        <b-form-invalid-feedback>This field is required</b-form-invalid-feedback>
                                    </b-form-group>
                                    <submit-button button_text="Update Backup Settings" :submitted="submitted" class="mt-3" />
                                </b-form>
                            </b-overlay>
                        </ValidationObserver>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Backup List</div>
                        <b-overlay :show="showOverlay">
                            <template v-slot:overlay>
                                <atom-loader></atom-loader>
                            </template>
                            <vue-good-table
                                :columns="table.columns"
                                :rows="backups"
                            >
                                <template #table-row="data">
                                    <span v-if="data.column.field === 'actions'">
                                        <a :href="route('admin.backups.edit', data.row.name)" class="text-muted">
                                            <i class="fas fa-download" title="Download Backup" v-b-tooltip.hover></i>
                                        </a>
                                        <!-- <i class="fas fa-heartbeat text-warning" title="Restore Backup" v-b-tooltip.hover></i> -->
                                        <i class="far fa-trash-alt text-danger" title="Delete Backup" v-b-tooltip.hover @click="deleteBackup(data.row.name)"></i>
                                    </span>
                                </template>
                            </vue-good-table>
                        </b-overlay>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../Layouts/app';

    export default {
        layout: App,
        props: {
            settings: {
                type:     Object,
                required: true,
            },
            backups: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                runningBackup: false,
                submitted: false,
                showOverlay: false,
                form: this.$inertia.form({
                    enabled: this.settings.enabled,
                    number:  this.settings.number,
                }),
                table: {
                    columns: [
                        {
                            label:   'File',
                            field:   'name',
                        },
                        {
                            label:   'Date',
                            field:   'date',
                        },
                        {
                            label:   'Actions',
                            field:   'actions',
                            sortable: false,
                        }
                    ]
                }
            }
        },
        created() {
            //
        },
        mounted() {
             //
        },
        computed: {
            buttonText()
            {
                return this.runningBackup ? 'Backing Up Now' : 'Run Backup';
            }
        },
        watch: {
             //
        },
        methods: {
            runBackup()
            {
                this.runningBackup = true;
                this.$inertia.get(route('admin.backups.show', 'run'), {
                    onFinish: ()=> {
                        this.runningBackup = false;
                    }
                });
            },
            submitForm()
            {
                this.submitted = true;
                this.form.post(route('admin.backups.store'), {
                    onFinish: () => {
                        this.submitted = false;
                    }
                });
            },
            deleteBackup(filename)
            {
                this.$bvModal.msgBoxConfirm('This Cannot Be Undone',
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
                        // this.loading = true;
                        this.showOverlay = true;
                        this.$inertia.delete(route('admin.backups.destroy', filename), {
                            onFinish: ()=> {
                                this.showOverlay = false;
                            }
                        });
                    }
                });
            }
        }
    }
</script>
