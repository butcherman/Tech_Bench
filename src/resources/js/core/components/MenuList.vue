<script setup lang="ts">
const props = defineProps<{
    menuList: menuItem[];
}>();
</script>

<template>
    <ul class="flex flex-col gap-1">
        <li v-for="item in menuList" :key="item.label">
            <Link
                :href="item.route"
                class="block p-2 w-full h-full hover:bg-slate-100 rounded-lg text-slate-700"
            >
                <fa-icon :icon="item.icon" />
                {{ item.label }}
            </Link>
        </li>
    </ul>
</template>
