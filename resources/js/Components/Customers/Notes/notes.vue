<template>
    <div>
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
            <b-card-text v-html="note.details" class="customer-note-minimized ql-editor"></b-card-text>
            <div>...</div>
            <span class="float-left text-muted">Click to Expand</span>
            <span class="float-right text-muted">Updated: {{note.updated_at}}</span>
        </b-card>
        <b-modal
            :title="openedNote.subject"
            ref="note-modal"
            size="xl"
            centered
            scrollable
            @hidden="note = {}"
        >
            <template #modal-footer="{ok}">
                <edit-note :cust_id="cust_id" :note="openedNote" :allow_share="allow_share" @completed="$refs['note-modal'].hide()"></edit-note>
                <b-button v-if="permissions.delete" variant="danger" @click="deleteNote(openedNote.note_id)">Delete</b-button>
                <b-button variant="primary" @click="ok()">Ok</b-button>
            </template>
            <b-overlay :show="loading">
                <template #overlay>
                    <atom-loader></atom-loader>
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
            </b-overlay>
        </b-modal>
    </div>
</template>

<script>
    import EditNote from './editNote.vue'
    export default {
        components: { EditNote },
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            notes: {
                type:     Array,
                required: true,
            },
            permissions: {
                type:     Object,
                required: true,
            },
            allow_share: {
                type:     Boolean,
                default:  false,
            }
        },
        data() {
            return {
                loading:    false,
                openedNote: {},
            }
        },
        methods: {
            openNote(note)
            {
                this.openedNote = note;
                this.$refs['note-modal'].show();
            },
            deleteNote(noteId)
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
                        this.$inertia.delete(route('customers.notes.destroy', noteId), {
                            onFinish: () => {
                                this.$refs['note-modal'].hide();
                                this.loading = false;
                            }
                        });
                    }
                });
            }
        },
    }
</script>
