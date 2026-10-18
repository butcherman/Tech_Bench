<script setup lang="ts">
import BannerAlert from "@/core/components/alerts/BannerAlert.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

const nonBaggedList = computed<string[]>(
    () =>
        Object.values(page.props.errors).filter(
            (error) => typeof error === "string",
        ) as string[],
);
</script>

<template>
    <div>
        <BannerAlert
            v-for="err in nonBaggedList"
            variant="danger"
            :text="err"
        />
    </div>
</template>
