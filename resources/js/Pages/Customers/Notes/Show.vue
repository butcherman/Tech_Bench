<template>
    <Head :title="customer.name" />
    <div>
        <div class="row">
            <div class="col-md-8">
                <CustomerDetails hide-fav />
            </div>
        </div>
        <div class="row">
            <div class="col">
                <Link
                    as="button"
                    :href="$route('customers.show', customer.slug)"
                    class="btn btn-info"
                >
                    <fa-icon icon="left-long" />
                    Back
                </Link>
                <DeleteButton class="float-end" @click="verifyDelete" />
                <EditNote :note="note" />
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <Overlay :loading="loading">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <span
                                    v-if="note.urgent"
                                    title="This note was marked as high importance"
                                    v-tooltip
                                >
                                    <fa-icon
                                        icon="fa-exclamation-circle"
                                        class="me-1 text-danger"
                                    />
                                </span>
                                <span
                                    v-if="note.shared"
                                    title="This note is shared across multiple sites"
                                    v-tooltip
                                >
                                    <fa-icon
                                        icon="fa-share"
                                        class="me-1 text-info"
                                    />
                                </span>
                                {{ note.subject }}
                                <!-- <span
                                class="badge bg-info float-end pointer"
                                title="Download Note"
                                v-tooltip
                            >
                                <fa-icon icon="fa-download" />
                            </span> -->
                            </div>
                            <div class="note-details" v-html="note.details" />
                        </div>
                        <div class="card-footer text-muted">
                            <div>
                                Created: {{ note.created_at }} by {{ note.author }}
                            </div>
                            <div v-if="note.updated_author">
                                Updated: {{ note.updated_at }} by
                                {{ note.updated_author }}
                            </div>
                        </div>
                    </div>
                </Overlay>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import CustomerDetails from "@/Components/Customer/CustomerDetails.vue";
import EditNote from "@/Components/Customer/Notes/EditNote.vue";
import DeleteButton from "@/Components/Base/Buttons/DeleteButton.vue";
import Overlay from '@/Components/Base/Overlay.vue';
import { router } from '@inertiajs/vue3';
import { ref, reactive, onMounted, provide, toRef, computed } from "vue";
import { customerKey, allowShareKey, toggleNotesLoadKey } from "@/SymbolKeys/CustomerKeys";
import { verifyModal } from "@/Modules/verifyModal.module";
import type { customerType, customerNoteType } from "@/Types";

const $route = route;
const props = defineProps<{
    customer: customerType;
    note: customerNoteType;
}>();

const loading = ref<boolean>(false);
const toggleLoad = () => {
    loading.value = !loading.value;
};
provide(toggleNotesLoadKey, toggleLoad);

provide(customerKey, ref(props.customer));

/**
 * Determine if entries for this customer are allowed to be shared with other customers
 */
const allowShare = computed(() => {
    return props.customer.child_count > 0 || props.customer.parent_id !== null;
});
provide(allowShareKey, allowShare);

const verifyDelete = () => {
    verifyModal('Please Verify').then(res => {
        console.log(res);

        if(res) {
            router.delete(route('customers.notes.destroy', props.note.note_id));
        }
    });
}
</script>

<script lang="ts">
export default { layout: App };
</script>

<style lang="scss">
.note-details {
    min-height: 25vh;
}
</style>
