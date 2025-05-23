<script setup lang="ts">
import Pagination from "@/Components/_Base/Pagination.vue";
import SearchBody from "./SearchBody.vue";
import {
    paginationData,
    searchParams,
    triggerPublicSearch,
    triggerSearch,
} from "@/Composables/TechTip/TipSearch.module";

const props = defineProps<{
    isPublic?: boolean;
}>();

/**
 * List of possible results to display in search results list.
 */
const paginationArray: number[] = [25, 50, 100];

/**
 * Navigate to a new page.
 */
const navigateToPage = (newPage: number): void => {
    console.log("navigate");
    searchParams.page = newPage;
    search();
};

/**
 * Determine if we are performing a public or private search.
 */
const search = () => {
    if (props.isPublic) {
        triggerPublicSearch();

        return;
    }

    triggerSearch();
};
</script>

<template>
    <div class="min-h-96">
        <table class="table-auto w-full">
            <thead class="border-b border-slate-300 border-collapse">
                <tr>
                    <th />
                    <th class="text-start">Title</th>
                    <th class="text-start">Date</th>
                </tr>
            </thead>
            <SearchBody :is-public="isPublic" />
            <tfoot>
                <tr>
                    <td colspan="3" class="p-3">
                        <div class="flex justify-between">
                            <select
                                v-model="searchParams.perPage"
                                @change="triggerSearch"
                            >
                                <option
                                    v-for="num in paginationArray"
                                    :value="num"
                                >
                                    {{ num }}
                                </option>
                            </select>
                            <Pagination
                                :current-page="paginationData.currentPage"
                                :total-pages="paginationData.totalPages"
                                @go-to-page="navigateToPage"
                                @next-page="navigateToPage"
                                @prev-page="navigateToPage"
                            />
                            <div>
                                Showing {{ paginationData.listFrom }} -
                                {{ paginationData.listTo }} of
                                {{ paginationData.listTotal }}
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>
