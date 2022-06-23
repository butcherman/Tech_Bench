<template>
   <b-modal
        :title="note.subject"
        :header-class="note.urgent ? 'bg-danger' : ''"
        ref="note-modal"
        size="xl"
        centered
        scrollable
        @hidden="$emit('closed')"
    >
        <template #modal-footer="{ok}">
            <edit-note
                v-if="customerStore.allowPermission('notes', 'update')"
                :note="note"
                @completed="$refs['note-modal'].hide()"
            />
            <delete-note
                v-if="customerStore.allowPermission('notes', 'delete')"
                :noteId="note.note_id"
                @loading="loading = true"
                @completed="$refs['note-modal'].hide()"
            />
            <b-button variant="primary" @click="ok()">Ok</b-button>
        </template>
        <b-overlay :show="loading">
            <template #overlay>
                <atom-loader />
            </template>
            <div v-html="note.details" class="ql-editor" />
            <div class="mt-4">
                <div v-if="note.updated_author" class="text-muted">
                    Updated: {{note.updated_at}} by {{note.updated_author}}
                </div>
                <div class="text-muted">
                    Created: {{note.created_at}} by {{note.author}}
                </div>
            </div>
        </b-overlay>
    </b-modal>
</template>

<script>
    import { useCustomerStore } from '../../../../Stores/customerStore';
    import { mapStores }        from 'pinia';

    export default {
        props: {
            note: {
                type    : Object,
                required: true,
            }
        },
        data() {
            return {
                loading: false,
            }
        },
        computed: {
            ...mapStores(useCustomerStore),
        },
        watch: {
            note()
            {
                if(this.note.subject)
                {
                    this.$refs['note-modal'].show();
                    this.loading = false;
                }
            }
        },
    }
</script>
