<template>
    <div class="bg-blue-300 rounded m-4 p-0">
        <v-breadcrumbs>
            <template v-for="crumb in breadcrumbs">
                <v-breadcrumbs-item>
                    <Link v-if="!crumb.is_current_page" :href="crumb.url">{{
                        crumb.title
                    }}</Link>
                    <span v-else>{{ crumb.title }}</span>
                </v-breadcrumbs-item>
                <v-breadcrumbs-divider v-if="!crumb.is_current_page">
                    <font-awesome-icon icon="caret-right" />
                </v-breadcrumbs-divider>
            </template>
        </v-breadcrumbs>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const breadcrumbs = computed<breadcrumbs[]>(
    () => usePage<pageProps>().props.breadcrumbs
);
</script>
