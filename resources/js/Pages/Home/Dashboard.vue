<template>
    <Head title="Dashboard" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <DashboardNotifications />
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Quick Links</div>
                        <div class="row justify-content-center">
                            <div v-if="!hasQuickLinks" class="text-muted col">
                                <p class="text-center">Looks a little lonely down here.</p>
                                <p class="text-center">Click the <fa-icon icon="fa-regular fa-bookmark" /> icon in a Customer or Tech Tip to add it as a quick link</p>
                            </div>
                            <div
                                v-if="bookmarks.customers.length"
                                class="col-md-5"
                            >
                                <BookmarkList type="customers" :list="bookmarks.customers" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Recent Visits</div>
                        <div class="row justify-content-center">
                            <div v-if="!hasRecentLinks" class="text-muted col">
                                <p class="text-center">Wow, you haven't been doing much.</p>
                                <p class="text-center">Start browsing around and your most recent Customers and Tech Tips visited will show up here.</p>
                            </div>
                            <div v-if="recent.customers.length" class="col-md-5">
                                <BookmarkList type="customers" :list="recent.customers" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import DashboardNotifications from "@/Components/Home/DashboardNotifications.vue";
import BookmarkList from '@/Components/Base/BookmarkList.vue';
import { computed } from "vue";

const props = defineProps<{
    bookmarks: bookmarkList;
    recent: bookmarkList;
}>();

const hasQuickLinks = computed(() => {
    return props.bookmarks.customers.length;
});

const hasRecentLinks = computed(() => {
    return props.recent.customers.length;
})
</script>

<script lang="ts">
export default { layout: App };
</script>
