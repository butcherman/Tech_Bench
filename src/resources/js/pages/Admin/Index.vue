<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import MenuList from "@/core/components/MenuList.vue";

defineOptions({ layout: AppLayout });

const props = defineProps<{
    menu: {
        [key: string]: MenuItem[];
    };
}>();
</script>

<template>
    <div class="flex flex-col gap-4">
        <div>
            <h1>System Administration</h1>
            <p>Manage Users and Application Features</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            <template v-for="(items, group) in menu" :key="group">
                <Card v-if="items.length" :title="group.toString()">
                    <MenuList :menu-list="items" compact />
                </Card>
            </template>
        </div>
    </div>
</template>
