<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";

const title = "Equipment Data Types";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <div class="md:w-3/4 text-center">
            <p>
                A listing of all Data Types that can be assigned to customer
                equipment are listed here. Additional settings can be applied to
                these fields by editing them.
            </p>
            <p>
                Use the <AddButton text="Add Data Type" size="small" /> button
                to create a new Data Type field. Use the <EditBadge /> button to
                modify an existing Data Type field, or the
                <DeleteBadge /> button to remove an unused field.
            </p>
            <p>
                <strong>Note:</strong> The <DeleteBadge /> button will only show
                if the field is not in use by any Equipment Type.
            </p>
        </div>
    </div>
</template>
