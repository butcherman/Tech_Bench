<template>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th></th>
                    <th>City</th>
                </tr>
            </thead>
            <CustomerSearchLoading v-if="isLoading" />
            <CustomerSearchEmpty v-else-if="!searchResults.length" />
            <CustomerSearchBody v-else />
            <tfoot>
                <tr>
                    <td colspan="3">
                        <span class="float-start w-auto">
                            <select
                                v-model="searchParams.perPage"
                                class="form-select"
                                @change="triggerSearch"
                            >
                                <option
                                    v-for="num in paginationArray"
                                    :value="num"
                                >
                                    {{ num }}
                                </option>
                            </select>
                        </span>
                        <span class="float-end w-auto">
                            Showing {{ paginationData.listFrom }} -
                            {{ paginationData.listTo }} of
                            {{ paginationData.listTotal }}
                        </span>
                        <Pagination
                            :current-page="paginationData.currentPage"
                            :total-pages="paginationData.totalPages"
                            @go-to-page="onChangePage"
                            @next-page="onChangePage(searchParams.page + 1)"
                            @prev-page="onChangePage(searchParams.page - 1)"
                        />
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script setup lang="ts">
import CustomerSearchBody from "./CustomerSearchBody.vue";
import CustomerSearchLoading from "./CustomerSearchLoading.vue";
import CustomerSearchEmpty from "./CustomerSearchEmpty.vue";
import Pagination from "@/Components/_Base/Pagination.vue";
import {
    isLoading,
    searchResults,
    searchParams,
    paginationData,
    triggerSearch,
} from "@/Modules/CustomerSearch.module";

const onChangePage = (newPage: number) => {
    searchParams.page = newPage;
    triggerSearch();
};

const paginationArray: number[] = [25, 50, 100];
</script>
