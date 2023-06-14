<template>
    <Head title="Administration" />
    <div>
        <div class="row justify-content-center">
            <div
                v-for="(options, group) in links"
                :key="group"
                class="col-md-5"
            >
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">{{ group }}</div>
                        <ul class="list-unstyled">
                            <li v-for="(link, key) in options" :key="key">
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
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps<{
    links: {
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
