<template>
    <div>
        <b-button variant="primary" pill size="sm" @click="openModal" :variant="button_variant">
            <i class="fas" :class="button_class" aria-hidden="true"></i>
            {{button_msg}}
        </b-button>
        <b-modal :title="button_msg" ref="customer-note-modal" size="lg" hide-footer @shown="showEditor = true" @hidden="showEditor = false">
            <b-form @submit="validateForm" ref="customer-note-form" :validated="validated" novalidate>
                <b-overlay :show="loading">
                    <template v-slot:overlay>
                        <atom-spinner
                            :animation-duration="1000"
                            :size="60"
                            color="#ff1d5e"
                            class="mx-auto"
                        />
                        <h4 class="text-center">Processing</h4>
                    </template>
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
                        <div v-if="noteErr" class="invalid-feedback d-block">You must enter some information</div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <b-form-checkbox v-model="form.urgent" switch>Mark Note As Urgent</b-form-checkbox>
                            <b-form-checkbox v-if="linked_site" v-model="form.shared" switch>Note is Shared Across All Sites</b-form-checkbox>
                        </div>
                    </div>
                    <form-submit
                        class="mt-3"
                        :button_text="button_msg"
                        :submitted="submitted"
                    ></form-submit>
                </b-overlay>
            </b-form>
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
            note_data: {
                type:     Object,
                required: false,
                default:  null,
            }
        },
        data() {
            return {
                validated: false,
                submitted: false,
                noteErr:   false,
                showEditor: false,
                form: {
                    cust_id:     this.cust_id,
                    subject:     null,
                    description: '',
                    urgent:      false,
                    shared:      false,
                }
            }
        },
        computed: {
            button_class()
            {
                return this.note_data ? 'fa-pencil-alt' : 'fa-plus';
            },
            button_msg()
            {
                return this.note_data ? 'Edit Note' : 'Add note';
            },
            button_variant()
            {
                return this.note_data ? 'warning' : 'primary';
            },
            edit_note()
            {
                return this.note_data ? true : false;
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
            openModal()
            {
                if(this.edit_note)
                {
                    this.form = {
                        cust_id:     this.cust_id,
                        subject:     this.note_data.subject,
                        description: this.note_data.description,
                        urgent:      this.note_data.urgent == 1 ? true : false,
                        shared:      this.note_data.shared == 1 ? true : false,
                    }
                }
                this.$refs['customer-note-modal'].show();
            },
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['customer-note-form'].checkValidity() === false || this.form.description == '')
                {
                     this.validated = true;
                     if(this.form.description == '')
                     {
                         this.noteErr = true;
                     }
                }
                else
                {
                    this.submitted = true;
                    this.loading   = true;
                    if(this.edit_note)
                    {
                        axios.put(this.route('customer.notes.update', this.note_data.note_id), this.form)
                            .then(res => {
                                this.$emit('note-updated');
                                this.$refs['customer-note-modal'].hide();
                            }).catch(error => this.eventHub.$emit('axiosError', error));
                    }
                    else
                    {
                        axios.post(this.route('customer.notes.store'), this.form)
                            .then(res => {
                                this.$emit('note-updated');
                                this.$refs['customer-note-modal'].hide();
                            }).catch(error => this.eventHub.$emit('axiosError', error));
                    }
                }
            }
        }
    }
</script>
