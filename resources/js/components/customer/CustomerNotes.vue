<template>
    <div class="card">
        <div class="card-header">
            Customer Notes:
            <b-button variant="primary" pill size="sm" class="float-right" @click="newNoteForm">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Add Note
            </b-button>
        </div>
        <div class="card-body">
            <div v-if="error">
                <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to load Notes...</h5>
            </div>
            <div v-else-if="loading">
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
                <h4 class="text-center">Loading Notes</h4>
            </div>
            <div v-else class="row">
                <div class="col-md-3 grid-margin stretch-card customer-note-card" v-for="note in notes" :key="note.note_id">
                    <div class="card">
                        <div :class="note.urgent == true ? 'card-header bg-danger' : 'card-header bg-info'" @click="openNote(note)">
                            {{note.subject}}
                        </div>
                        <div class="card-body" v-html="note.description"></div>
                    </div>
                </div>
            </div>
        </div>
        <b-modal title="Note Details" ref="noteDetailsModal" size="xl">
            <div class="card">
                <div :class="details.urgent == true ? 'card-header bg-danger' : 'card-header bg-info'">
                    {{details.subject}}
                    <a :href="route('customer.download-note', details.note_id)" class="float-right text-white" title="Download as PDF" v-b-tooltip.hover><i class="fas fa-download"></i></a>
                </div>
                <div class="card-body bigger-note" v-html="details.description"></div>
            </div>
            <template slot="modal-footer" slot-scope="{ok}">
                <b-button variant="danger" size="sm" pill @click="deleteNote">Delete Note</b-button>
                <b-button variant="warning" size="sm" pill @click="editNoteForm">Edit Note</b-button>
                <b-button variant="primary" size="sm" pill @click="ok()">Close</b-button>
            </template>
        </b-modal>
        <note-form ref="customer-note-form" :cust_id="cust_id" @completed="getNotes"></note-form>
    </div>
</template>

<script>
export default {
    props: {
        cust_id: {
            type:     Number,
            required: true,
        },
        linked: {
            type:     Boolean,
            required: false,
            default:  false,
        }
    },
    data() {
        return {
            //
            error:   false,
            loading: true,
            newNote: false,
            notes:   [],
            details: [],
        }
    },
    mounted() {
            //
        this.getNotes();
    },
    methods: {
        getNotes()
        {
            this.$refs['noteDetailsModal'].hide();
            this.loading = true;
            axios.get(this.route('customer.notes.show', this.cust_id))
                .then(res => {
                    this.notes = res.data;
                    this.loading = false;
                }).catch(error => this.error = true);
        },
        openNote(note)
        {
            this.details = note;
            this.$refs['noteDetailsModal'].show();
        },
        newNoteForm()
        {
            this.$refs['customer-note-form'].initNewNote(this.details);
        },
        editNoteForm()
        {
            this.$refs['customer-note-form'].initEditNote(this.details);
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
                        this.$refs['noteDetailsModal'].hide();
                        this.loading = true;
                        axios.delete(this.route('customer.notes.destroy', this.details.note_id))
                            .then(res => {
                                this.getNotes();
                            }).catch(error => this.$bvModal.msgBoxOk('Something bad happened.  Please try again later.'));
                    }
                });
        }
    }
}
</script>
