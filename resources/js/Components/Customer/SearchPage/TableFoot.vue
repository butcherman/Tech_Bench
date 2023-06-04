<template>
    <tfoot>
        <tr>
            <td colspan="3">
                <div class="row m-0">
                    <div class="col-auto">
                        Per Page:
                        <select
                            id="res-per-page"
                            @change="triggerSearch"
                            v-model="searchParam.perPage"
                        >
                            <option v-for="num in paginationArray">
                                {{ num }}
                            </option>
                        </select>
                    </div>
                    <div class="col">
                        <ul class="pagination justify-content-center">
                            <li
                                class="page-item"
                                :class="{
                                    disabled: paginationData.currentPage === 1,
                                }"
                                @click="goToPage(1)"
                            >
                                <span
                                    class="page-link pointer"
                                    title="First Page"
                                    v-tooltip
                                >
                                    <fa-icon icon="fa-angles-left" />
                                </span>
                            </li>
                            <li
                                class="page-item"
                                :class="{
                                    disabled: paginationData.currentPage === 1,
                                }"
                                @click="
                                    goToPage(paginationData.currentPage - 1)
                                "
                            >
                                <span
                                    class="page-link pointer"
                                    title="Next Page"
                                    v-tooltip
                                >
                                    <fa-icon icon="fa-angle-left" />
                                </span>
                            </li>
                            <li
                                v-for="page in paginationData.pageArr"
                                class="page-item"
                                :class="{
                                    active: paginationData.currentPage === page,
                                }"
                                @click="goToPage(page)"
                            >
                                <span class="page-link pointer">
                                    {{ page }}
                                </span>
                            </li>
                            <li
                                class="page-item"
                                :class="{
                                    disabled:
                                        paginationData.currentPage ===
                                        paginationData.numPages,
                                }"
                                @click="
                                    goToPage(paginationData.currentPage + 1)
                                "
                            >
                                <span
                                    class="page-link pointer"
                                    title="Next Page"
                                    v-tooltip
                                >
                                    <fa-icon icon="fa-angle-right" />
                                </span>
                            </li>
                            <li
                                class="page-item"
                                :class="{
                                    disabled:
                                        paginationData.currentPage ===
                                        paginationData.numPages,
                                }"
                                @click="goToPage(paginationData.numPages)"
                            >
                                <span
                                    class="page-link pointer"
                                    title="Last Page"
                                    v-tooltip
                                >
                                    <fa-icon icon="fa-angles-right" />
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        Showing
                        {{ paginationData.listFrom }}
                        -
                        {{ paginationData.listTo }}
                        of
                        {{ paginationData.listTotal }}
                    </div>
                </div>
            </td>
        </tr>
    </tfoot>
</template>

<script setup lang="ts">
import {
    triggerSearch,
    paginationData,
    searchParam,
    paginationArray,
} from "@/State/Customer/SearchState";

const goToPage = (page: number): void => {
    searchParam.page = page;
    triggerSearch();
};
</script>
