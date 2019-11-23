<template>
    <div>
        <div v-if="error">
            <h5 class="text-center">Problem Loading Data...</h5>
        </div>
        <div v-else-if="loading">
            <h5 class="text-center">Loading Notes</h5>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        </div>
        <div v-else>
            <div class="row">
                <div class="col-12">
                    <h4 v-if="notes.length == 0" class="text-center">No Notes</h4>
                    <button class="btn btn-info float-right" v-b-modal.note-form-modal><i class="ti-plus"></i> Add Note</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 grid-margin stretch-card customer-note-card" v-for="note in notes" :key="note.note_id">
                    <div class="card">
                            <div :class="note.urgent == true ? 'card-header bg-danger' : 'card-header bg-info'" @click="openNote(note)">
                                {{note.subject}}
                            </div>
                            <div class="card-body" v-html="note.description"></div>
                        </div>
                </div>
            </div>
            <b-modal title="Note Details" ref="noteDetailsModal" size="xl" id="note-details-modal">
                <div class="card">
                    <div :class="details.urgent == true ? 'card-header bg-danger' : 'card-header bg-info'">
                        {{details.subject}}
                        <a :href="route('customer.download-note', details.note_id)" class="float-right text-white" title="Download as PDF" v-b-tooltip.hover><i class="ti-download"></i></a>
                    </div>
                    <div class="card-body bigger-note" v-html="details.description"></div>
                </div>
                <template slot="modal-footer" slot-scope="{ok}">
                    <b-button variant="danger" @click="deleteNote">Delete Note</b-button>
                    <b-button variant="warning" @click="editNote">Edit Note</b-button>
                    <b-button variant="primary" @click="ok()">Close</b-button>
                </template>
            </b-modal>
            <b-modal :title="modalTitle" id="note-form-modal" ref="noteFormModal" size="xl" hide-footer>
                <b-form @submit="submitNote" novalidate :validated="validated" ref="noteForm">
                    <b-form-group
                        label="Note Title"
                            label-for="note-title"
                    >
                        <b-form-input
                            id="note-title"
                                type="text"
                                v-model="form.title"
                                required
                                placeholder="Enter Descriptive Title"
                        ></b-form-input>
                        <b-form-invalid-feedback>You must enter a title</b-form-invalid-feedback>
                    </b-form-group>
                    <div class="pt-2 pb-2">
                        <editor v-if="modalShown" :init="{plugins: 'autolink', height:500}" id="note-details" v-model="form.note"></editor>
                        <div v-if="noteError" class="invalid-feedback d-block">You must enter some information</div>
                    </div>
                    <div class="row justify-content-center mt-4">
                        <div class="col-6 col-md-2 order-2 order-md-1">
                            <div class="onoffswitch">
                                <input type="checkbox" name="markUrgent" class="onoffswitch-checkbox" id="markUrgent" v-model="form.urgent">
                                <label class="onoffswitch-label" for="markUrgent">
                                    <span class="yesnoswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 align-self-center order-1 order-md-2">
                            <h5>Mark this note as urgent</h5>
                        </div>
                    </div>
                    <b-button type="submit" block variant="primary" class="pad-top" :disabled="button.disable">
                        <span class="spinner-border spinner-border-sm text-danger" v-show="button.disable"></span>
                        {{button.text}}
                    </b-button>
                </b-form>
            </b-modal>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'cust_id',
        ],
        data () {
            return {
                loading: true,
                error: false,
                isLoading: false,
                validated: false,
                noteError: false,
                edit: false,
                modalTitle: 'New Customer Note',
                modalShown: false,
                form: {
                    cust_id: this.cust_id,
                    title: '',
                    note: '',
                    urgent: false,
                },
                button: {
                    diable: false,
                    text: 'Create New Note',
                },
                notes: [],
                details: [],
            }
        },
        created()
        {
            this.getNotes();
            this.$root.$on('bv::modal::shown', (bvEvent, modalID) => {
                this.modalShown = true;
            });
            this.$root.$on('bv::modal::hidden', (bvEvent, modalID) => {
                this.modalShown = false;
                if(modalID === 'note-form-modal')
                {
                    this.resetForm();
                }
            });
        },
        methods: {
            getNotes()
            {
                axios.get(this.route('customer.notes.show', this.cust_id))
                    .then(res => {
                        this.notes = res.data;
                        this.loading = false;
                    }).catch(error => this.error = true);
            },
            openNote(note)
            {
                this.$bvModal.show('note-details-modal');
                this.details = note;
            },
            editNote()
            {
                this.$bvModal.hide('note-details-modal');
                this.modalTitle  = 'Edit Customer Note';
                this.button.text = 'Update Note';
                this.edit        = this.details.note_id;
                this.form.title  = this.details.subject;
                this.form.note   = this.details.description;
                this.form.urgent = this.details.urgent;
                this.$bvModal.show('note-form-modal');
            },
            deleteNote()
            {
                this.$bvModal.msgBoxConfirm('Please confirm you want to delete note.', {
                    title: 'Are You Sure?',
                    size: 'md',
                    okVariant: 'danger',
                    okTitle: 'Yes',
                    cancelTitle: 'No',
                    centered: true,
                }).then(res => {
                    if(res)
                    {
                        this.$refs.noteDetailsModal.hide();
                        this.loading = true;
                        axios.delete(this.route('customer.notes.destroy', this.details.note_id))
                            .then(res => {
                                this.getNotes();
                                this.resetForm();
                            }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                    }
                });
            },
            submitNote(e)
            {
                e.preventDefault();
                if(this.$refs.noteForm.checkValidity() === false || this.form.note == '')
                {
                    this.validated = true;
                    if(this.form.note == '')
                    {
                        this.noteError = true;
                    }
                }
                else
                {
                    this.button.disable = true;
                    this.button.text = 'Processing...';
                    if(this.edit)
                    {
                        axios.put(this.route('customer.notes.update', this.edit), this.form)
                            .then(res => {
                                this.resetForm();
                                this.$refs.noteFormModal.hide();
                                this.getNotes();
                            }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                    }
                    else
                    {
                        axios.post(this.route('customer.notes.store'), this.form)
                            .then(res => {
                                this.resetForm();
                                this.$refs.noteFormModal.hide();
                                this.getNotes();
                            }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                    }
                }
            },
            resetForm()
            {
                this.validated      =  false;
                this.noteError      = false;
                this.edit           = false;
                this.modalTitle     = 'New Customer Note';
                this.form.title     = '';
                this.form.note      = '';
                this.form.urgent    = false;
                this.button.disable = false;
                this.button.text    = 'Create New Note';
            }
        }
    }
</script>
