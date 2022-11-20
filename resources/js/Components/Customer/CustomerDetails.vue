<template>
    <div>
        <h3>
            <span
                class="pointer"
                :class="bookmarkClass"
                :title="bookmarkTitle"
                v-tooltip
                @click="toggleBookmark"
            >
                <fa-icon :icon="bookmarkIcon" />
            </span>
            {{customer?.name}}
            <small>

            </small>
        </h3>
    </div>
</template>

<script setup lang="ts">
    import { customerType } from '@/Types';
    import { ref, reactive, onMounted, inject, computed } from 'vue';


    const { isBookmark, toggleBookmark } = inject('bookmark');
    const customer = inject<customerType>('customer');





    /**
     * Customer Bookmark Section
     */
    const bookmarkIcon = computed<string>(() => {
        return isBookmark.value ? 'fa-solid fa-bookmark' : 'fa-regular fa-bookmark';
    });
    const bookmarkClass = computed<string>(() => {
        return isBookmark.value ? 'bookmark-checked' : 'bookmark-checked';
    });
    const bookmarkTitle = computed<string>(() => {
        return isBookmark.value ? 'Remove From Bookmarks' : 'Add to Bookmarks';
    });
</script>
