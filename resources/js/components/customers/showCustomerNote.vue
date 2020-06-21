<template>
    <div class="col-md-3 grid-margin stretch-card customer-note-card">
        <div class="card">
            <div class="card-header" :class="note_class" @click="$refs['note-modal'].show()">
                {{note_data.subject}}
            </div>
            <div class="card-body" v-html="note_data.description"></div>
            <b-modal ref="note-modal" size="xl" title="Note Details">
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
                    <div class="card">
                        <div class="card-header" :class="note_class">
                            {{note_data.subject}}
                        </div>
                        <div class="card-body bigger-note" v-html="note_data.description"></div>
                    </div>
                </b-overlay>
                <template slot="modal-footer" slot-scope="{ok}">
                    <b-button variant="danger" size="sm" pill @click="deleteNote"><i class="fas fa-trash-alt"></i> Delete Note</b-button>
                    <edit-customer-note :note_data="note_data" :cust_id="note_data.cust_id" :linked_site="linked_site" @note-updated="updated"></edit-customer-note>
                    <b-button variant="primary" size="sm" pill @click="ok()">Close</b-button>
                </template>
            </b-modal>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            note_data: {
                type:     Object,
                required: true,
            },
            linked_site: {
                type:     Boolean,
                required: false,
                default:  false,
            }
        },
        data() {
            return {
                showOverlay: false,
            }
        },
        computed: {
            note_class()
            {
                return this.note_data.urgent ? 'bg-danger' : 'bg-info';
            }
        },
        methods: {
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
                    this.showOverlay = true;
                    axios.delete(this.route('customer.notes.destroy', this.note_data.note_id))
                        .then(res => {
                            this.$emit('note-updated');
                            this.$refs['note-modal'].hide();
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                });
            },
            updated()
            {
                this.$emit('note-updated');
                this.$refs['note-modal'].hide();
            },
        }
    }
</script>
