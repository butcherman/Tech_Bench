<template>
    <nav
        v-if="breadcrumbs.length"
        id="breadcrumb-wrapper"
        class="alert alert-primary"
    >
        <ol class="breadcrumb">
            <li
                v-for="breadcrumb in breadcrumbs"
                class="breadcrumb-item"
                :class="{ active: breadcrumb.is_current_page }"
            >
                <Link
                    v-if="!breadcrumb.is_current_page"
                    :href="breadcrumb.url"
                    class="text-muted"
                >
                    {{ breadcrumb.title }}
                </Link>
                <span v-else class="text-bold">
                    {{ breadcrumb.title }}
                </span>
            </li>
        </ol>
    </nav>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const breadcrumbs = computed<breadcrumbs[]>(
    () => usePage<pageProps>().props.breadcrumbs
);
</script>

<style scoped lang="scss">
#breadcrumb-wrapper {
    padding: 0.5rem;
    --bs-breadcrumb-divider: ">";
    ol {
        margin: 0;
        a {
            text-decoration: none;
        }
    }
}
</style>
