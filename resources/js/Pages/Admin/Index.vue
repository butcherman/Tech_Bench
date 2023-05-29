<template>
    <Head title="Administration" />
    <div>
        <div
            v-for="(options, key) in links"
            :key="key"
            class="row justify-content-center"
        >
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">{{ key }}:</div>
                        <ul class="list-unstyled">
                            <li v-for="(opt, key2) in options" :key="key2">
                                <Link
                                    :href="opt.link"
                                    class="text-secondary text-decoration-none admin-link"
                                >
                                    <fa-icon :icon="opt.icon" />
                                    {{ opt.name }}
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
import App from "@/Layouts/app.vue";

defineProps<{
    links: {
        [key: string]: {
            icon: string;
            link: string;
            name: string;
        }[];
    };
}>();
</script>

<script lang="ts">
export default { layout: App };
</script>

<style scoped>
.admin-link:hover {
    font-weight: bold;
}
</style>
