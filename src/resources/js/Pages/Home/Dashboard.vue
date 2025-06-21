<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import Card from "@/Components/_Base/Card.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { Deferred } from "@inertiajs/vue3";

interface linkInterface {
    techTips: techTip[];
    customers: customer[];
}

defineProps<{
    bookmarks?: linkInterface;
    recent?: linkInterface;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex flex-col items-center gap-3">
        <Card class="tb-card-lg" title="Bookmarks">
            <Deferred data="bookmarks">
                <template #fallback>
                    <AtomLoader />
                </template>
                <div v-if="bookmarks" class="flex flex-col md:flex-row gap-2">
                    <div class="flex-1">
                        <h4 class="text-center">Customer Bookmarks</h4>
                        <ResourceList
                            empty-text="No Customer Bookmarks"
                            label-field="name"
                            :list="bookmarks?.customers"
                            :link-fn="
                                (cust) => $route('customers.show', cust.slug)
                            "
                            center
                        />
                    </div>
                    <div class="flex-1">
                        <h4 class="text-center">Tech Tip Bookmarks</h4>
                        <ResourceList
                            empty-text="No Tech Tip Bookmarks"
                            label-field="subject"
                            :list="bookmarks?.techTips"
                            :link-fn="
                                (tip) => $route('tech-tips.show', tip.slug)
                            "
                            center
                        />
                    </div>
                </div>
            </Deferred>
        </Card>
        <Card class="tb-card-lg" title="Recent Visits">
            <Deferred data="recent">
                <template #fallback>
                    <AtomLoader />
                </template>
                <div v-if="recent" class="flex flex-col md:flex-row gap-2">
                    <div class="flex-1">
                        <h4 class="text-center">Customer Recent Visits</h4>
                        <ResourceList
                            empty-text="No Recent Customer Visits"
                            label-field="name"
                            :list="recent?.customers"
                            :link-fn="
                                (cust) => $route('customers.show', cust.slug)
                            "
                            center
                        />
                    </div>
                    <div class="flex-1">
                        <h4 class="text-center">Tech Tip Recent Visits</h4>
                        <ResourceList
                            empty-text="No Recent Tech Tip Visits"
                            label-field="subject"
                            :list="recent?.techTips"
                            :link-fn="
                                (tip) => $route('tech-tips.show', tip.slug)
                            "
                            center
                        />
                    </div>
                </div>
            </Deferred>
        </Card>
    </div>
</template>
