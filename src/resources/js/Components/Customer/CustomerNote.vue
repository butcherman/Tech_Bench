<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <AlertButton
                    v-if="changeAlert.notes"
                    title="Notes Updated.  Refresh for most recent data"
                />
                <RefreshButton
                    :only="['notes']"
                    @loading-start="toggleLoading('notes')"
                    @loading-complete="clearAlert('notes')"
                />
                Notes:
                <Link :href="addRoute">
                    <AddButton
                        v-if="permissions.notes.create"
                        class="float-end"
                        text="Add Note"
                        small
                        pill
                    />
                </Link>
            </div>
            <Overlay :loading="loading.notes">
                <div v-if="!notes.length">
                    <h6 class="text-center">No Notes</h6>
                </div>
                <div
                    v-for="note in paginatedNotes"
                    :key="note.note_id"
                    class="my-1"
                >
                    <div
                        class="card text-bg-light px-2 py-1 pointer customer-note-minimized"
                        @click="peakNote(note)"
                    >
                        <div class="card-title text-center text-md-start">
                            <span
                                v-if="note.urgent"
                                class="float-start float-md-none"
                                title="This note was marked with high importance"
                                v-tooltip
                            >
                                <fa-icon
                                    icon="exclamation-circle"
                                    class="me-1 text-danger"
                                />
                            </span>
                            {{ note.subject }}
                            <div class="float-md-end">
                                <span class="d-none d-md-inline">
                                    Last Updated -
                                </span>
                                {{ note.updated_at }}
                            </div>
                        </div>
                        <div
                            v-html="note.details"
                            class="note-details-minimized text-muted"
                        />
                    </div>
                </div>
                <nav class="row justify-content-end my-2">
                    <div class="col-md-4" v-if="totalPages > 1">
                        <Pagination
                            :current-page="currentPage"
                            :total-pages="totalPages"
                            @go-to-page="goToPage"
                            @next-page="nextPage"
                            @prev-page="prevPage"
                        />
                    </div>
                    <div
                        class="col-md-4 text-center border py-2"
                        v-if="notes.length > 1"
                    >
                        <h6>Sort By</h6>
                        <button
                            class="btn btn-light btn-sm"
                            :class="{ active: sortBy === 'urgent' }"
                            @click="sortNotes('urgent')"
                        >
                            Urgent
                        </button>
                        <button
                            class="btn btn-light btn-sm"
                            :class="{ active: sortBy === 'subject' }"
                            @click="sortNotes('subject')"
                        >
                            Subject
                        </button>
                        <button
                            class="btn btn-light btn-sm"
                            :class="{ active: sortBy === 'updated_at' }"
                            @click="sortNotes('updated_at')"
                        >
                            Last Updated
                        </button>
                    </div>
                </nav>
            </Overlay>
        </div>
        <Modal
            ref="customerNoteModal"
            title="Note Details"
            size="xl"
            hide-footer
            @hidden="activeNote = null"
        >
            <CustomerNoteDetails
                v-if="activeNote"
                :note="activeNote"
                :equipment="equipment"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import AddButton from "../_Base/Buttons/AddButton.vue";
import Pagination from "../_Base/Pagination.vue";
import Modal from "../_Base/Modal.vue";
import RefreshButton from "../_Base/Buttons/RefreshButton.vue";
import AlertButton from "../_Base/Buttons/AlertButton.vue";
import Overlay from "../_Base/Loaders/Overlay.vue";
import CustomerNoteDetails from "./CustomerNoteDetails.vue";
import { ref, computed } from "vue";
import { sortDataObject } from "@/Modules/SortDataObject.module";
import {
    customer,
    notes,
    permissions,
    currentSite,
    loading,
    toggleLoading,
    changeAlert,
    clearAlert,
} from "@/State/CustomerState";

const props = defineProps<{
    equipment?: customerEquipment;
}>();

const customerNoteModal = ref<InstanceType<typeof Modal> | null>(null);
const activeNote = ref<customerNote | null>(null);
const peakNote = (note: customerNote) => {
    activeNote.value = note;
    customerNoteModal.value?.show();
};

/**
 * Determine which route the add button takes based on if there is a customer
 * site currently selected
 */
const addRoute = computed(() => {
    if (props.equipment) {
        return route("customers.equipment.notes.create", [
            customer.value.slug,
            props.equipment.cust_equip_id,
        ]);
    }

    if (currentSite.value) {
        return route("customers.site.notes.create", [
            customer.value.slug,
            currentSite.value.site_slug,
        ]);
    }

    return route("customers.notes.create", customer.value.slug);
});

/**
 * Sort Notes
 */
const sortBy = ref<keyof customerNote>("urgent");
const sortOrder = ref<"asc" | "desc">("asc");
const sortNotes = (sortField: keyof customerNote) => {
    if (sortField === sortBy.value) {
        if (sortOrder.value === "asc") {
            sortOrder.value = "desc";
        } else {
            sortOrder.value = "asc";
        }
    } else {
        sortOrder.value = "asc";
        sortBy.value = sortField;
    }
};

const sortedNotes = computed<customerNote[]>(() =>
    sortDataObject<customerNote>(notes.value, sortOrder.value, sortBy.value)
);

/**
 * Pagination Logic
 */
const perPage = 5;
const currentPage = ref<number>(1);
const totalPages = computed<number>(() =>
    Math.ceil(notes.value.length / perPage)
);
const paginatedNotes = computed<customerNote[]>(() =>
    sortedNotes.value.slice(
        (currentPage.value - 1) * perPage,
        currentPage.value * perPage
    )
);

const prevPage = (): void => {
    currentPage.value--;
};

const nextPage = (): void => {
    currentPage.value++;
};

const goToPage = (numPage: number): void => {
    currentPage.value = numPage;
};
</script>

<style scoped lang="scss">
.customer-note-minimized {
    overflow: hidden;
    .note-details-minimized {
        max-height: 45px;
    }
}
</style>
