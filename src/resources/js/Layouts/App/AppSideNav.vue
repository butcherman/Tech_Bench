<template>
    <v-navigation-drawer
        class="pt-5"
        :width="228"
        :model-value="navbarActive"
        mobile-breakpoint="md"
    >
        <template v-for="item in app.navbar" :key="item.name">
            <v-list-item>
                <Link :href="item.route">
                    <font-awesome-icon :icon="item.icon" />
                    {{ item.name }}
                </Link>
            </v-list-item>
        </template>
    </v-navigation-drawer>
</template>

<script setup lang="ts">
import { useAppStore } from "@/Stores/AppStore";

defineProps<{
    navbarActive: boolean | null;
}>();

const app = useAppStore();
</script>
