<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    menuList: MenuItem[];
    compact?: boolean;
}>();

const paddingStyle = computed(() => (props.compact ? "p-0.5" : "p-2"));
</script>

<template>
    <ul class="flex flex-col gap-1">
        <li v-for="item in menuList" :key="item.label">
            <Link
                :href="item.route"
                class="block w-full h-full hover:bg-slate-100 rounded-lg text-slate-700"
                :class="[paddingStyle]"
            >
                <fa-icon :icon="item.icon" />
                {{ item.label }}
            </Link>
        </li>
    </ul>
</template>
