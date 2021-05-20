<template>
    <div>
        <div class="card-title">
            Notes:
            <new-note-modal v-if="permissions.create" :cust_id="cust_id" @completed="getNotes"></new-note-modal>
        </div>
        <b-overlay :show="loading">
            <template #overlay>
                <atom-loader text="Loading Notes..."></atom-loader>
            </template>
            <div v-if="notes.length == 0">
                <h4 class="text-center">No Notes</h4>
            </div>
            <b-card
                v-for="note in notes"
                :key="note.note_id"
                class="pointer grid-margin"
                @click="openNote(note)"
            >
                <b-card-title>
                    <i v-if="note.urgent" class="fas fa-exclamation-circle text-danger" title="Important!" v-b-tooltip.hover></i>
                    <i v-if="note.shared" class="fas fa-share" title="Note Shared Across Sites" v-b-tooltip.hover></i>
                    {{note.subject}}
                </b-card-title>
                <b-card-text v-html="note.details" class="customer-note-minimized ql-editor">
                </b-card-text>
                <div>...</div>
                <span class="float-left text-muted">Click to Expand</span>
                <span class="float-right text-muted">Updated: {{note.updated_at}}</span>
            </b-card>
        </b-overlay>
        <b-modal
            :title="openedNote.subject"
            ref="note-modal"
            size="xl"
            centered
            scrollable
            @hidden="note = {}"
        >
            <template #modal-footer="{ok}">
                <edit-note-modal v-if="permissions.update" :cust_id="cust_id" :note="openedNote" @completed="getNotes"></edit-note-modal>
                <b-button v-if="permissions.delete" variant="danger" @click="deleteNote(openedNote.note_id)">Delete</b-button>
                <b-button variant="primary" @click="ok()">Ok</b-button>
            </template>
            <div v-html="openedNote.details" class="ql-editor"></div>
            <div class="mt-4">
                <div v-if="openedNote.updated_author" class="text-muted">
                    Updated: {{openedNote.updated_at}} by {{openedNote.updated_author}}
                </div>
                <div class="text-muted">
                    Created: {{openedNote.created_at}} by {{openedNote.author}}
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import EditNoteModal from './Notes/editNoteModal.vue';
    import NewNoteModal  from './Notes/newNoteModal.vue'
    import VClamp        from 'vue-clamp';

    export default {
        components: {
            EditNoteModal,
            NewNoteModal,
            VClamp,
        },
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            customer_notes: {
                type:     Array,
                required: true,
            },
            permissions: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                loading:    false,
                notes:      this.customer_notes,
                openedNote: {}
            }
        },
        methods: {
            openNote(note)
            {
                this.openedNote = note;
                this.$refs['note-modal'].show();
            },
            getNotes()
            {
                this.$refs['note-modal'].hide();
                this.loading = true;
                axios.get(this.route('customers.notes.show', this.cust_id))
                    .then(res => {
                        this.notes   = res.data;
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            deleteNote(noteID)
            {
                this.$bvModal.msgBoxConfirm('Please Verify',
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
                            this.$refs['note-modal'].hide();
                            axios.delete(this.route('customers.notes.destroy', noteID))
                                .then(() => {
                                    this.getNotes();
                                }).catch(error => this.eventHub.$emit('axiosError', error));
                        }
                    });
            }

        },
    }
</script>
