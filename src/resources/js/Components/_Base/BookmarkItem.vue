<script setup lang="ts">
import okModal from "@/Modules/okModal";
import axios from "axios";
import { ref, computed } from "vue";

const props = defineProps<{
    isBookmark: boolean;
    toggleRoute: string;
}>();

/**
 * Component State
 */
const isActive = ref<boolean>(props.isBookmark);
const isLoading = ref<boolean>(false);

/**
 * Font Awesome Icon for the Bookmark Button.
 */
const bookmarkIcon = computed<string>(() => {
    if (isLoading.value) {
        return "fa-solid fa-circle fa-beat";
    }

    return isActive.value ? "fa-solid fa-bookmark" : "fa-regular fa-bookmark";
});

/**
 * Class to apply to Bookmark Item
 */
const bookmarkClass = computed<string>(() => {
    if (isLoading.value) {
        return "fa-beat-fade";
    }

    return isActive.value ? "bookmark-checked" : "bookmark-unchecked";
});

/**
 * Tooltip to show on the Bookmark Item
 */
const bookmarkTitle = computed<string>(() => {
    return isActive.value ? "Remove From Bookmarks" : "Add to Bookmarks";
});

/**
 * Handle the Bookmark Click event.
 */
const toggleBookmark = (): void => {
    isLoading.value = true;

    axios
        .post(props.toggleRoute, { value: !isActive.value })
        .then((res) => {
            if (res.data.success) {
                isActive.value = !isActive.value;
            }
        })
        .catch((err) => {
            okModal(err.message);
        })
        .finally(() => {
            isLoading.value = false;
        });
};
</script>

<template>
    <button v-tooltip="bookmarkTitle" @click="toggleBookmark">
        <fa-icon :icon="bookmarkIcon" :class="bookmarkClass" />
    </button>
</template>

<style scoped>
.bookmark-checked {
    color: #d46e70;
}

.bookmark-unchecked {
    color: #c4c4c4;
}
</style>
