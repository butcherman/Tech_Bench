<script setup lang="ts">
import { Breadcrumb } from "primevue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const breadcrumbs = computed<breadcrumb[]>(
    () => usePage<pageProps>().props.breadcrumbs
);
</script>

<template>
    <Breadcrumb
        v-if="breadcrumbs.length"
        :model="breadcrumbs"
        class="text-center bg-blue-300 rounded-lg text-slate-600"
    >
        <template #item="{ item }">
            <Link v-if="!item.is_current_page" :href="item.url">
                {{ item.title }}
            </Link>
            <span v-else>{{ item.title }}</span>
        </template>
    </Breadcrumb>
</template>
