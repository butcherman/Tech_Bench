<template>
    <div>
        <UserNotifications />
        <div class="row my-4 resource-links">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Bookmarks:</div>
                        <ResourceLinks
                            :tech-tips="bookmarks.techTips"
                            :customers="[]"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-4 resource-links">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Recent:</div>
                        <ResourceLinks
                            :tech-tips="recent.techTips"
                            :customers="[]"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserNotifications from "@/Components/User/UserNotifications.vue";
import ResourceLinks from "@/Components/Home/ResourceLinks.vue";

defineProps<{
    bookmarks: {
        techTips: techTip[];
    };
    recent: {
        techTips: techTip[];
    };
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
