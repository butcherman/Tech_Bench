<template>
    <div class="grid gap-4">
        <h1 class="font-bold mb-5">Hello {{ app.user?.full_name }}</h1>
        <Card title="Bookmarks">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                <div>
                    <h5 class="text-center font-bold">Customer Bookmarks</h5>
                    <ResourceList :list="bookmarks.customers" />
                </div>
                <div>
                    <h5 class="text-center font-bold">Tech Tip Bookmarks</h5>
                    <ResourceList :list="bookmarks.techTips" />
                </div>
            </div>
        </Card>
        <Card title="Recent Visits">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                <div>
                    <h5 class="text-center font-bold">Customers</h5>
                    <ResourceList :list="recent.customers" />
                </div>
                <div>
                    <h5 class="text-center font-bold">Tech Tips</h5>
                    <ResourceList :list="recent.techTips" />
                </div>
            </div>
        </Card>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/Main/Card.vue";
import { ref, reactive, onMounted } from "vue";
import { useAppStore } from "@/Stores/AppStore";
import ResourceList from "@/Components/Main/ResourceList.vue";

defineProps<{
    bookmarks: {
        techTips: resourceList[];
        customers: resourceList[];
    };
    recent: {
        techTips: resourceList[];
        customers: resourceList[];
    };
}>();

const app = useAppStore();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
