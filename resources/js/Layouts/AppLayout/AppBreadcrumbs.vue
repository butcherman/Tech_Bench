<template>
    <div class="breadcrumbs-wrapper alert alert-info">
        <nav v-if="breadcrumbs.length">
            <ol class="breadcrumb">
                <li
                    v-for="crumb in breadcrumbs"
                    :key="crumb.title"
                    :class="{ active: crumb.is_current_page }"
                    class="breadcrumb-item"
                >
                    <Link
                        v-if="!crumb.is_current_page"
                        class="text-muted"
                        :href="crumb.url"
                    >
                        {{ crumb.title }}
                    </Link>
                    <span v-else class="text-muted">
                        {{ crumb.title }}
                    </span>
                </li>
            </ol>
        </nav>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const page: pageData = usePage();
const breadcrumbs = computed(() => page.props.breadcrumbs);
</script>

<style scoped lang="scss">
.breadcrumbs-wrapper {
    margin: 0 !important;
    margin-bottom: 15px !important;
    padding: 5px !important;
    nav {
        margin-bottom: 0 !important;
        .breadcrumb {
            margin: 0 !important;
            padding: 3px;
            a {
                text-decoration: none;
            }
        }
    }
}
</style>
