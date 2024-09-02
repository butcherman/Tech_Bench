<template>
    <div>
        <Head title="Dashboard" />
        <h5>Hello {{ app.user?.full_name }}</h5>

        <Link href="/notification-test" class="btn btn-info mt-4"
            >Test Notification</Link
        >
        <div class="row my-4 resource-links">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Bookmarks:</div>
                        <ResourceLinks
                            :tech-tips="bookmarks.techTips"
                            :customers="bookmarks.customers"
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
                            :customers="recent.customers"
                        />
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
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
