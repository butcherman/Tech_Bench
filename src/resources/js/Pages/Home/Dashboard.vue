<template>
    <div>
        <Head title="Dashboard" />
        <h5>Hello {{ app.user?.full_name }}</h5>
        <div class="row my-4 resource-links">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <span
                                class="float-end pointer"
                                title="Show/Hide Bookmarks"
                                v-tooltip
                                @click="showBookmarks = !showBookmarks"
                            >
                                <fa-icon :icon="showBookmarkIcon" />
                            </span>
                            Bookmarks:
                        </div>
                        <Transition
                            @enter="growShow"
                            @leave="shrinkHide"
                            :css="false"
                        >
                            <ResourceLinks
                                v-show="showBookmarks"
                                :tech-tips="bookmarks.techTips"
                                :customers="bookmarks.customers"
                            />
                        </Transition>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-4 resource-links">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <span
                                class="float-end pointer"
                                title="Show/Hide Bookmarks"
                                v-tooltip
                                @click="showRecents = !showRecents"
                            >
                                <fa-icon :icon="showRecentIcon" />
                            </span>
                            Recent Visits:
                        </div>
                        <Transition
                            @enter="growShow"
                            @leave="shrinkHide"
                            :css="false"
                        >
                            <ResourceLinks
                                v-show="showRecents"
                                :tech-tips="recent.techTips"
                                :customers="recent.customers"
                            />
                        </Transition>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import ResourceLinks from "@/Components/Home/ResourceLinks.vue";
import { useAppStore } from "@/Store/AppStore";
import { ref, computed } from "vue";
import { growShow, shrinkHide } from "@/Modules/Animation.module";

defineProps<{
    bookmarks: {
        techTips: techTip[];
        customers: customer[];
    };
    recent: {
        techTips: techTip[];
        customers: customer[];
    };
}>();

const app = useAppStore();
const showBookmarks = ref(true);
const showRecents = ref(true);

const showBookmarkIcon = computed(() =>
    showBookmarks.value
        ? "down-left-and-up-right-to-center"
        : "up-right-and-down-left-from-center"
);
const showRecentIcon = computed(() =>
    showRecents.value
        ? "down-left-and-up-right-to-center"
        : "up-right-and-down-left-from-center"
);
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
