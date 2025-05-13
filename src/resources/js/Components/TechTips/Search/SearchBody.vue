<script setup lang="ts">
import SearchBodyEmpty from "./SearchBodyEmpty.vue";
import SearchBodyLoading from "./SearchBodyLoading.vue";
import { handleLinkClick } from "@/Composables/links.module";
import {
    searchResults,
    isLoading,
} from "@/Composables/TechTip/TipSearch.module";

const onRowClick = (event: MouseEvent, row: techTip): void => {
    handleLinkClick(event, route("tech-tips.show", row.slug));
};

const getRowBgClass = (rowIndex: number): string => {
    return rowIndex % 2 === 0 ? "bg-slate-100" : "";
};
</script>

<template>
    <tbody>
        <SearchBodyLoading v-if="isLoading" />
        <SearchBodyEmpty v-else-if="!searchResults.length" />
        <template v-else>
            <tr
                v-for="(tip, index) in searchResults"
                :key="tip.tip_id"
                class="border-b hover:bg-slate-200 pointer"
                :class="getRowBgClass(index)"
                @click="onRowClick($event, tip)"
            >
                <td class="py-2">
                    <span v-if="tip.sticky" class="text-danger">
                        <fa-icon icon="thumbtack" class="rotate-45" />
                    </span>
                </td>
                <td class="py-2">{{ tip.subject }}</td>
                <td class="py-2 px-2">{{ tip.created_at }}</td>
            </tr>
        </template>
    </tbody>
</template>
