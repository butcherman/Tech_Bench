<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const title = computed<string>(
    () => usePage<pageProps>().props.breadcrumbs.pop()?.title ?? "Help"
);

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <div class="md:w-3/4 text-center">
            <p>
                Search for a customer or customer site here. You can perform the
                search by the customer name, a specific site name, or
                customer/site ID number.
            </p>
            <p>
                To show a list of all sites associated with the customer, turn
                on the "Show Customer Sites" switch.
            </p>
        </div>
    </div>
</template>
