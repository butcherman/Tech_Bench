<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { dashboard } from "@/wayfinder/routes";

const page = usePage();
const breadcrumbs = computed(() => page.props.breadcrumbs);
</script>

<template>
    <div
        v-if="breadcrumbs.length"
        class="bg-blue-50 border border-blue-100 text-blue-600 rounded-lg p-4"
    >
        <ul class="flex gap-2">
            <li>
                <Link :href="dashboard.url()">
                    <fa-icon icon="house" />
                </Link>
            </li>
            <li>
                <fa-icon icon="chevron-right" />
            </li>
            <template v-for="link in breadcrumbs" :key="link.url">
                <template v-if="link.is_current_page">
                    <li>
                        <span class="text-slate-800 trunkcate">
                            {{ link.title }}
                        </span>
                    </li>
                </template>
                <template v-else>
                    <li class="shrink-0 sm:hidden">
                        <span>...</span>
                    </li>
                    <li class="hidden shrink-0 sm:block">
                        <Link :href="link.url"> {{ link.title }}</Link>
                    </li>
                    <li>
                        <fa-icon icon="chevron-right" />
                    </li>
                </template>
            </template>
        </ul>
    </div>
</template>
