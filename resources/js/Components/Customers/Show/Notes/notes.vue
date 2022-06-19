<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title clearfix">
                Notes
                <new-note v-if="customerStore.allowPermission('notes', 'create')" />
            </div>
            <div>
                <div v-if="!customerStore.notes.length">
                    <h4 class="text-center">No Notes</h4>
                </div>
                <b-card
                    v-else
                    v-for="note in customerStore.notes"
                    :key="note.note_id"
                    class="pointer grid-margin"
                    @click="openedNote = note"
                >
                    <b-card-title>
                        <i
                            v-if="note.urgent"
                            class="fas fa-exclamation-circle text-danger"
                            title="Important!"
                            v-b-tooltip.hover
                        />
                        <i
                            v-if="note.shared"
                            class="fas fa-share"
                            title="Note Shared Across Sites"
                            v-b-tooltip.hover
                        />
                        {{note.subject}}
                    </b-card-title>
                    <b-card-text
                        v-html="note.details"
                        class="customer-note-minimized ql-editor"
                    />
                    <div>...</div>
                    <span class="float-left text-muted">
                        Click to Expand
                    </span>
                    <span class="float-right text-muted">
                        Updated: {{note.updated_at}}
                    </span>
                </b-card>
            </div>
        </div>
        <show-note
            :note="openedNote"
            @closed="openedNote = {}"
        />
    </div>
</template>

<script>
    import { useCustomerStore } from '../../../../Stores/customerStore';
    import { mapStores }        from 'pinia';

    export default {
        data() {
            return {
                openedNote: {},
            }
        },
        computed: {
            ...mapStores(useCustomerStore),
        },
    }
</script>
