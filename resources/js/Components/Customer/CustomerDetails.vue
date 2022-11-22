<template>
    <div>
        <h3>
            <span
                v-if="bookmarkLoading"
                class="spinner-grow spinner-grow-sm my-2"
            />
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
            {{ customer?.name }}
            <small>
                <!-- linked -->
            </small>
        </h3>
        <h5 v-if="customer?.dba_name">
            AKA - {{ customer.dba_name }}
        </h5>
    </div>
</template>

<script setup lang="ts">
    import { customerBookmarkInjection, customerType } from '@/Types';
    import { ref, reactive, onMounted, inject, computed } from 'vue';

    const { isBookmark,
            bookmarkLoading,
            toggleBookmark } = inject('bookmark') as customerBookmarkInjection;
    const customer           = inject<customerType>('customer');

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
