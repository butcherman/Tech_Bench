<template>
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    Customer Notes:
                    <edit-customer-note class="float-md-right" :cust_id="cust_id" :linked_site="linked_site" @note-updated="getNotes"></edit-customer-note>
                </div>
                <div class="card-body">
                    <div v-if="loading">
                        <atom-spinner
                            :animation-duration="1000"
                            :size="60"
                            color="#ff1d5e"
                            class="mx-auto"
                        />
                        <h4 class="text-center">Loading...</h4>
                    </div>
                    <div v-else-if="error">
                        <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to load Notes...</h5>
                    </div>
                    <div v-else-if="!notes.length">
                        <h5 class="text-center text-muted">No Notes Have Been Created Yet</h5>
                    </div>
                    <div v-else class="row">
                        <show-customer-note v-for="note in notes" :key="note.note_id" :note_data="note" :linked_site="linked_site" @note-updated="getNotes"></show-customer-note>
                    </div>
                </div>
            </div>
        </div>
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
            }
        },
        data() {
            return {
                loading: true,
                error:   false,
                notes:   [],
            }
        },
        mounted() {
             this.getNotes();
             this.eventHub.$on('parent-linked', data => {
                 this.getNotes();
             });
        },
        methods: {
            getNotes()
            {
                this.loading = true;
                axios.get(this.route('customer.notes.show', this.cust_id))
                    .then(res => {
                        this.notes = res.data;
                        this.loading = false;
                    }).catch(error => this.error = true);
            },
        }
    }
</script>
