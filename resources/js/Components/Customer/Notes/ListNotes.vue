<template>
    <div v-if="!notes.length">
        <h5 class="text-center">No Notes Assigned to this Customer</h5>
    </div>
    <div v-else>
        <Link
            :href="
                $route('customers.notes.show', [customer.slug, note.note_id])
            "
            v-for="note in paginatedData"
            :key="note.note_id"
        >
            <div class="card customer-note-minimized my-2 pointer">
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
                            <fa-icon icon="fa-share" class="me-1 text-info" />
                        </span>
                        {{ note.subject }}
                        <span class="float-end text-muted">
                            Last Updated - {{ note.updated_at }}
                        </span>
                    </div>
                    <div
                        class="note-details-minimized text-muted"
                        v-html="note.details"
                    />
                </div>
            </div>
        </Link>
        <nav v-if="totalPages > 1">
            <ul class="pagination pagination-sm justify-content-center">
                <li class="page-item">
                    <span
                        class="page-link pointer"
                        :class="{ disabled: page === 1 }"
                        @click="prevPage"
                    >
                        &laquo;
                    </span>
                </li>
                <li v-for="index in totalPages" :key="index" class="page-item">
                    <span
                        class="page-link pointer"
                        :class="{ active: page === index }"
                        @click="goToPage(index)"
                    >
                        {{ index }}
                    </span>
                </li>
                <li class="page-item">
                    <span
                        class="page-link pointer"
                        :class="{ disabled: page === totalPages }"
                        @click="nextPage"
                    >
                        &raquo;
                    </span>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, inject } from "vue";
import { customerKey } from "@/SymbolKeys/CustomerKeys";
import type { Ref } from "vue";

const props = defineProps<{
    notes: customerNote[];
}>();

const $route = route;
const customer = inject(customerKey) as Ref<customer>;

/**
 * Pagination Logic
 */
const perPage = 5;
const page = ref<number>(1);
const totalPages = computed(() => Math.ceil(props.notes.length / perPage));
const paginatedData = computed(() =>
    props.notes.slice((page.value - 1) * perPage, page.value * perPage)
);

const prevPage = (): void => {
    page.value--;
};

const nextPage = (): void => {
    page.value++;
};

const goToPage = (numPage: number): void => {
    page.value = numPage;
};
</script>

<style lang="scss">
a {
    text-decoration: none;
    .customer-note-minimized {
        overflow: hidden;
        .note-details-minimized {
            max-height: 50px;
        }
    }
}
</style>
