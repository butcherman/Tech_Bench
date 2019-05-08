<template>
    <div>
       <div class="row pad-bottom">
           <div class="col-md-3 pad-bottom customer-note-card" v-for="note in notes">
               <div class="card">
                    <div :class="note.urgent == true ? 'card-header bg-danger' : 'card-header bg-info'" @click="openNote(note)">
                        {{note.subject}}
                        <a href="#" class="float-right text-white" title="Download as PDF" v-b-tooltip.hover><i class="fa fa-download"></i></a>
                    </div>
                    <div class="card-body" v-html="note.description"></div>
                </div>
           </div>
       </div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-sm-4">
                <b-button variant="info" v-b-modal.new-note-modal block>Create New Note</b-button>
            </div>
        </div>
        <b-modal title="Create New Note" id="new-note-modal" ref="newNoteModal" size="lg" hide-footer>
            <b-form @submit="createNote">
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
               </b-form-group>
                <div class="pt-2 pb-2">
                    <editor :init="{plugins: 'autolink', height: 500}" v-model="form.note"></editor>
                </div>
                <b-form-checkbox
                    v-model="form.urgent"
                    value="urgent"
                    unchecked-value="not-urgent"
                >Mark Note As Urgent</b-form-checkbox>
                <b-button type="submit" block variant="primary" class="pad-top">Create Note</b-button>
            </b-form>
        </b-modal>
        <b-modal title="Update Note" id="edit-note-modal" ref="editNoteModal" size="lg" hide-footer>
            <b-form @submit="updateNote">
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
               </b-form-group>
                <div class="pt-2 pb-2">
                    <editor :init="{plugins: 'autolink', height: 500}" v-model="form.note"></editor>
                </div>
                <b-form-checkbox
                    v-model="form.urgent"
                    value="urgent"
                    unchecked-value="not-urgent"
                >Mark Note As Urgent</b-form-checkbox>
                <b-button type="submit" block variant="primary" class="pad-top">Update Note</b-button>
            </b-form>
        </b-modal>
        <b-modal title="Note Details" ref="noteDetailsModal" size="lg">
            <div class="card">
                <div :class="show.urgent == true ? 'card-header bg-danger' : 'card-header bg-info'" @click="openNote(note)">
                    {{show.subject}}
                    <a href="#" class="float-right text-white" title="Download as PDF" v-b-tooltip.hover><i class="fa fa-download"></i></a>
                </div>
                <div class="card-body bigger-note" v-html="show.description"></div>
                <div class="card-footer">
                   <b-button variant="info" block @click="editNote(show)">Edit Note</b-button>
                </div>
            </div>
            <template slot="modal-footer" slot-scope="{ok, cancel}">
                <click-confirm>
                    <b-button variant="danger" @click="deleteNote(show)">Delete Note</b-button>
                </click-confirm>
                <b-button variant="primary" @click="ok()">Ok</b-button>
            </template>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: [
            'cust_id',
            'add_note_route',
        ],
        data () {
            return {
                form: {
                    custID: this.cust_id,
                    title: '',
                    note: '',
                    urgent: 'not-urgent',
                },
                notes: [],
                show: [],
            }
        },
        created() 
        {
            this.getNotes();
        },
        methods: {
            getNotes()
            {
                axios.get(this.add_note_route+'/'+this.cust_id)
                    .then(res => {
                        this.notes = res.data;
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
            createNote(e)
            {
                e.preventDefault();
                
                axios.post(this.add_note_route, this.form)
                    .then(res => {
                        if(res.data.success)
                        {
                            this.$refs.newNoteModal.hide();
                            this.resetNoteForm();
                            this.getNotes();
                        }
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
            editNote(note)
            {
                this.$refs.editNoteModal.show();
                this.form.title  = note.subject;
                this.form.note   = note.description;
                this.form.urgent = note.urgent == true ? 'urgent' : 'not-urgent';
                this.form.noteID = note.note_id;
            },
            updateNote(e)
            {
                e.preventDefault();
                
                axios.put(this.add_note_route+'/'+this.form.noteID, this.form)
                    .then(res => {
                        if(res.data.success)
                        {
                            this.$refs.editNoteModal.hide();
                            this.$refs.noteDetailsModal.hide();
                            this.getNotes();
                            this.resetNoteForm();
                        }
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
            resetNoteForm()
            {
                this.form.title = '';
                this.form.note = '';
                this.form.urgent = 'not-urgent';
            },
            openNote(note)
            {
                this.$refs.noteDetailsModal.show();
                this.show = note;
            },
            deleteNote(note)
            {
                axios.delete(this.add_note_route+'/'+note.note_id)
                    .then(res => {
                        this.$refs.noteDetailsModal.hide();
                        this.getNotes();
                        this.resetNoteForm();
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
        }
    }
</script>
