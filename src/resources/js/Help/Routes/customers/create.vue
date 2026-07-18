<script setup lang="ts">
import CustomerDetailsForm from "@/Help/Forms/Customer/CustomerDetailsForm.vue";
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
            <p>Use this form to create a new Customer Instance.</p>
        </div>
        <CustomerDetailsForm />
    </div>
</template>
