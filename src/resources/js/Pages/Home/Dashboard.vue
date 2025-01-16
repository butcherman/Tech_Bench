<template>
    <div class="grid gap-2">
        <h2 class="pb-2">Hello {{ app.user?.full_name }}</h2>
        <Card title="Bookmarks">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <h4 class="text-center">Customer Bookmarks</h4>
                    <ResourceList
                        label-field="name"
                        empty-text="No Customer Bookmarks Yet"
                        :link-fn="(cust) => $route('customers.show', cust.slug)"
                        :list="bookmarks.customers"
                        center
                    />
                </div>
                <div>
                    <h4 class="text-center">Tech Tip Bookmarks</h4>
                    <ResourceList
                        label-field="subject"
                        empty-text="No Tech Tip Bookmarks Yet"
                        :link-fn="(tip) => $route('tech-tips.show', tip.slug)"
                        :list="bookmarks.techTips"
                        center
                    />
                </div>
            </div>
        </Card>
        <Card title="Recent Visits">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <h4 class="text-center">Customers</h4>
                    <ResourceList
                        label-field="name"
                        empty-text="You have not visited any customers yet"
                        :link-fn="(cust) => $route('customers.show', cust.slug)"
                        :list="recent.customers"
                        center
                    />
                </div>
                <div>
                    <h4 class="text-center">Tech Tips</h4>
                    <ResourceList
                        label-field="subject"
                        empty-text="You have not viewed any Tech Tips yet"
                        :link-fn="(tip) => $route('tech-tips.show', tip.slug)"
                        :list="recent.techTips"
                        center
                    />
                </div>
            </div>
        </Card>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import { useAppStore } from "@/Stores/AppStore";
import ResourceList from "@/Components/_Base/ResourceList.vue";

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
