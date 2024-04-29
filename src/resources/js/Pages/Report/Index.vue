<template>
    <div>
        <Head title="Reports" />
        <div class="row justify-content-center">
            <div v-for="(items, category) in menu" class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">{{ category }}</div>
                        <ul class="list-group">
                            <template v-for="item in items">
                                <Link
                                    as="li"
                                    :href="item.route"
                                    class="list-group-item pointer"
                                >
                                    {{ item.name }}
                                </Link>
                            </template>
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
    menu: { [key: string]: { name: string; route: string }[] };
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
