<template>
    <div class="row justify-content-center">
        <Head title="Administration" />
        <template v-for="(items, group) in menu" :key="group">
            <div v-if="items.length" class="col-md-5 mt-2">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">{{ group }}</div>
                        <ul class="list-unstyled">
                            <li v-for="(link, key) in items" :key="key">
                                <Link
                                    :href="link.route"
                                    class="text-secondary text-decoration-none"
                                >
                                    <fa-icon :icon="link.icon" />
                                    {{ link.name }}
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps<{
    menu: {
        [key: string]: {
            icon: string;
            route: string;
            name: string;
        }[];
    };
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
