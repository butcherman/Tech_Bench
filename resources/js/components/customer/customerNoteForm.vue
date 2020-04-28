<template>
    <b-modal :title="modalTitle" ref="noteModal" hide-footer size="xl" @close="resetModal" @shown="showEditor = true">
        <div v-if="error">
            <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Something bad happened...</h5>
        </div>
        <b-overlay v-else :show="loading">
            <template v-slot:overlay>
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
                <h4 v-show="submitted" class="text-center">Processing...</h4>
            </template>
            <b-form @submit="validateForm" ref="noteForm" :validated="validated" novalidate>
                <b-form-group
                    label="Note Title"
                    label-for="note-title"
                >
                    <b-form-input
                        id="note-title"
                            type="text"
                            v-model="form.subject"
                            required
                            placeholder="Enter Descriptive Title"
                    ></b-form-input>
                    <b-form-invalid-feedback>You must enter a title</b-form-invalid-feedback>
                </b-form-group>
                <div class="pt-2 pb-2">
                    <editor v-if="showEditor" :init="{plugins: 'autolink', height:500}" id="note-details" v-model="form.description"></editor>
                    <div v-if="noteError" class="invalid-feedback d-block">You must enter some information</div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <b-form-checkbox v-model="form.urgent" switch>Mark Note As Urgent</b-form-checkbox>
                        <b-form-checkbox v-model="form.shared" switch>Note is Shared Across All Sites</b-form-checkbox>
                    </div>
                </div>
                <form-submit
                    class="mt-3"
                    :button_text="btnText"
                    :submitted="submitted"
                ></form-submit>
            </b-form>
        </b-overlay>
    </b-modal>
</template>

<script>
    export default {
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
        },
        data() {
            return {
                error:     false,
                submitted:  false,
                validated:  false,
                noteError:  false,
                showEditor: false,
                newNote: true,
                form: {
                    cust_id:     this.cust_id,
                    subject:     null,
                    description: '',
                    urgent:      false,
                    shared:      false,
                },
            }
        },
        computed: {
            modalTitle()
            {
                return this.newNote ? 'Add New Note' : 'Edit Note';
            },
            btnText()
            {
                return this.newNote ? 'Add New Note' : 'Update Note';
            },
            loading:
            {
                get: function()
                {
                    return this.showEditor ? false : true;
                },
                set: function()
                {
                    return true;
                }
            }
        },
        methods: {
            //  Initialize the modal to assign new note
            initNewNote()
            {
                this.newNote          = true,
                this.noteError        = false;
                this.form.subject     = null;
                this.form.description = '';
                this.form.urgent      = false;
                this.form.shared      = false;
                this.$refs['noteModal'].show();
                this.loading = false;
            },
            //  Initialize the modal to edit existing note
            initEditNote(data)
            {
                console.log(data);
                this.newNote = false,
                this.form = data;
                this.form.shared = this.form.shared ? true : false;
                this.form.urgent = this.form.urgent ? true : false;
                this.$refs['noteModal'].show();
                this.loading = false;
            },
            //  Reset the modal
            resetModal()
            {
                this.submitted        = false;
                this.validated        = false;
                this.newNote          = true;
                this.noteError        = false;
                this.form.cust_id     = this.cust_id,
                this.form.subject     = null;
                this.form.description = '';
                this.form.urgent      = false;
                this.form.shared      = false;
                this.showEditor       = false;
                this.$emit('completed');
            },

            //  Validate the note and submit it
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs.noteForm.checkValidity() === false || this.form.description == '')
                {
                    this.validated = true;
                    if(this.form.description == '')
                    {
                        this.noteError = true;
                    }
                }
                else
                {
                    this.submitted = true;

                    if(this.newNote)
                    {
                        axios.post(this.route('customer.notes.store'), this.form)
                            .then(res => {
                                this.$emit('completed');
                                this.$refs['noteModal'].hide();
                                this.submitted = false;
                            }).catch(error => this.$bvModal.msgBoxOk('Something bad happened.  Please try again later.'));
                    }
                    else
                    {
                        axios.put(this.route('customer.notes.update', this.form.note_id), this.form)
                            .then(res => {
                                this.$emit('completed');
                                this.$refs['noteModal'].hide();
                                this.submitted = false;
                            }).catch(error => this.$bvModal.msgBoxOk('Something bad happened.  Please try again later.'));
                    }
                }
            }
        }
    }
</script>
