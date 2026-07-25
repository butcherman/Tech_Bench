<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import MenuList from "@/core/components/MenuList.vue";

const props = defineProps<{
    menu: {
        [key: string]: menuItem[];
    };
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        <template v-for="(items, group) in menu" :key="group">
            <Card v-if="items.length" :title="group.toString()" size="large">
                <MenuList :menu-list="items" />
            </Card>
        </template>
    </div>
</template>
