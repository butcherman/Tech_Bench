<template>
    <span v-if="bookmarkLoading" class="spinner-grow spinner-grow-sm my-2" />
    <span
        v-else
        class="pointer"
        :class="bookmarkClass"
        :title="bookmarkTitle"
        v-tooltip
        @click="toggleBookmark"
    >
        <fa-icon :icon="bookmarkIcon" />
    </span>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import axios from "axios";
import ok from "@/Modules/ok";

const props = defineProps<{
    isFav: boolean;
    toggleRoute: string;
    modelId: number;
}>();

const isBookmark = ref<boolean>(props.isFav);
const bookmarkLoading = ref<boolean>(false);

const bookmarkIcon = computed<string>(() => {
    return isBookmark.value ? "fa-solid fa-bookmark" : "fa-regular fa-bookmark";
});
const bookmarkClass = computed<string>(() => {
    return isBookmark.value ? "bookmark-checked" : "bookmark-checked";
});
const bookmarkTitle = computed<string>(() => {
    return isBookmark.value ? "Remove From Bookmarks" : "Add to Bookmarks";
});

const toggleBookmark = (): void => {
    bookmarkLoading.value = true;
    axios
        .post(props.toggleRoute, {
            model_id: props.modelId,
            state: !isBookmark.value,
        })
        .then(() => {
            isBookmark.value = !isBookmark.value;
        })
        .catch((err) => {
            ok(err);
        })
        .finally(() => {
            bookmarkLoading.value = false;
        });
};
</script>

<style scoped lang="scss">
.bookmark-checked {
    color: #d46e70;
}

.bookmark-unchecked {
    color: #c4c4c4;
}
</style>
