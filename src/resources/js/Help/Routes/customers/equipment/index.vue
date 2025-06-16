<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
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
                Any equipment assigned to the customer will be shown here. To
                see additional information about this equipment, select the
                equipment name. To attach a new piece of equipment to this
                customer, select
                <AddButton text="Add Equipment" size="small" pill />
            </p>
        </div>
    </div>
</template>
