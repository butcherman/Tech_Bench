<template>
    <div class="bg-blue-300 rounded m-4 p-0">
        <v-breadcrumbs>
            <template v-for="(crumb, index) in breadcrumbs">
                <span
                    v-if="index == breadcrumbs.length - 2"
                    class="md:hidden inline"
                >
                    <v-breadcrumbs-divider v-if="!crumb.is_current_page">
                        <Link v-if="!crumb.is_current_page" :href="crumb.url">
                            <font-awesome-icon icon="caret-left" />
                        </Link>
                    </v-breadcrumbs-divider>
                </span>
                <v-breadcrumbs-item>
                    <Link
                        v-if="!crumb.is_current_page"
                        :href="crumb.url"
                        class="hidden md:inline"
                    >
                        {{ crumb.title }}
                    </Link>
                    <span v-else>{{ crumb.title }}</span>
                </v-breadcrumbs-item>
                <span class="hidden md:inline">
                    <v-breadcrumbs-divider v-if="!crumb.is_current_page">
                        <font-awesome-icon icon="caret-right" />
                    </v-breadcrumbs-divider>
                </span>
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
