<script setup lang="ts" generic="T">
import { computed } from "vue";
import { handleLinkClick } from "../../Composables/links.module";

// TODO - Fix type Errors...

const emit = defineEmits<{
    "row-clicked": [event: MouseEvent, item: T];
}>();

const props = defineProps<{
    center?: boolean;
    compact?: boolean;
    emptyText?: string;
    labelField?: keyof T;
    list: T[];
    noBorder?: boolean;
    linkFn?: (row: any) => string;
}>();

const paddingClass = computed(() => (props.compact ? "p-1" : "p-3"));

const onRowClicked = (event: MouseEvent, item: T): void => {
    emit("row-clicked", event, item);

    if (props.linkFn) {
        let url = props.linkFn(item);
        handleLinkClick(event, url);
    }
};
</script>

<template>
    <div class="h-full">
        <ul class="rounded-lg border-collapse" :class="{ border: !noBorder }">
            <li v-if="!list.length">
                <slot name="empty-slot">
                    <h4 class="text-center">
                        {{ emptyText ?? "No Data" }}
                    </h4>
                </slot>
            </li>
            <li
                v-for="(item, index) in list"
                :key="index"
                class="border-collapse"
                :class="[
                    {
                        border: !noBorder,
                        'text-center': center,
                        'pointer hover:bg-slate-200': linkFn,
                    },
                    paddingClass,
                ]"
                @click="onRowClicked($event, item)"
            >
                <slot name="list-item" :item="item">
                    {{ item[labelField] ?? item }}
                </slot>
                <div class="float-end">
                    <slot name="actions" :item="item" />
                </div>
            </li>
        </ul>
    </div>
</template>
