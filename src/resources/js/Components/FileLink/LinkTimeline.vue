<script setup lang="ts">
import AtomLoader from "../_Base/Loaders/AtomLoader.vue";
import BaseBadge from "../_Base/Badges/BaseBadge.vue";
import Card from "../_Base/Card.vue";
import Collapse from "../_Base/Collapse.vue";
import RefreshButton from "../_Base/Buttons/RefreshButton.vue";
import ResourceList from "../_Base/ResourceList.vue";
import { computed, ref } from "vue";
import { Deferred } from "@inertiajs/vue3";

defineProps<{
    timeline?: fileLinkTimeline[];
}>();

/**
 * Show/Hide Timeline
 */
const showTimeline = ref<boolean>(false);
const shrinkIcon = computed<string>(() =>
    showTimeline.value
        ? "down-left-and-up-right-to-center"
        : "up-right-and-down-left-from-center"
);
const shrinkTooltip = computed<string>(() =>
    showTimeline.value ? "Hide Timeline" : "Show Timeline"
);
</script>

<template>
    <Card>
        <template #title>
            <RefreshButton :only="['timeline', 'uploaded-files']" />
            Timeline
        </template>
        <template #append-title>
            <BaseBadge
                :icon="shrinkIcon"
                v-tooltip.left="shrinkTooltip"
                @click="showTimeline = !showTimeline"
            />
        </template>
        <Collapse :show="showTimeline">
            <Deferred data="timeline">
                <template #fallback>
                    <div class="flex justify-center">
                        <AtomLoader />
                    </div>
                </template>
                <template v-if="timeline?.length">
                    <ResourceList :list="timeline">
                        <template #list-item="{ item, index }">
                            <div>
                                <strong>Date:</strong> {{ item.created_at }}
                            </div>
                            <div v-if="isNaN(item.added_by as number)">
                                <strong>Created By:</strong> {{ item.added_by }}
                            </div>
                            <ul
                                class="px-4 py-2 border border-slate-300 rounded-lg"
                            >
                                <li
                                    v-if="!item.files.length && !item.notes"
                                    class="flex justify-evenly"
                                >
                                    <fa-icon
                                        class="pt-1 text-danger"
                                        icon="triangle-exclamation"
                                    />
                                    <span> File Deleted </span>
                                    <fa-icon
                                        class="pt-1 text-danger"
                                        icon="triangle-exclamation"
                                    />
                                </li>
                                <li v-for="file in item.files">
                                    File
                                    <a :href="file.href" class="text-blue-500">
                                        {{ file.file_name }}
                                    </a>
                                    added
                                </li>
                                <li v-if="item.notes">
                                    <strong>Note:</strong>
                                    {{ item.notes }}
                                </li>
                            </ul>
                        </template>
                    </ResourceList>
                </template>
            </Deferred>
        </Collapse>
    </Card>
</template>
