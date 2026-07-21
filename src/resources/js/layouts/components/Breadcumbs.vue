<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";

const { props } = usePage();
</script>

<template>
    <div class="bg-blue-300 rounded-lg opacity-85 p-4">
        <ul class="flex gap-2">
            <li v-for="link in props.breadcrumbs" :key="link.url">
                <div v-if="link.is_current_page">
                    {{ link.title }}
                </div>
                <div v-else>
                    <Link :href="link.url"> {{ link.title }}</Link>
                    <fa-icon icon="angle-right" class="text-muted" />
                </div>
            </li>
        </ul>
    </div>
</template>
