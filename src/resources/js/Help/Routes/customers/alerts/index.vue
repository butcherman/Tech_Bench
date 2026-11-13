<script setup lang="ts">
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
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
                Customer Alerts are displayed on all pages for this customer.
                They will be a large alert below the customer name and above all
                customer information.
            </p>
            <p>
                Alerts should be used to convey important information about the
                customer that should not be overlooked when a user goes to the
                customers information pages.
            </p>
            <p>
                Select <AddButton pill size="small" text="Create Alert" /> to
                create a new alert.
            </p>
            <p>
                Select the <EditBadge /> button to modify an existing alert, or
                the
                <DeleteBadge />
                button to remove the alert.
            </p>
        </div>
    </div>
</template>
