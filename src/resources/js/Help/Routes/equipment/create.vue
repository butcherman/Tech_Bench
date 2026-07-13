<script setup lang="ts">
import EquipmentForm from "@/Help/Forms/Equipment/EquipmentForm.vue";

defineProps<{
    publicTips: boolean;
}>();

const title = "Create New Equipment";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <div class="md:w-3/4 text-center">
            <p class="text-center">
                Use this page to create a new equipment type. The Equipment Type
                can be assigned to customers and be used to track information
                for that Equipment. It can also be assigned to Tech Tips to
                assist others with helpful information about the Equipment.
            </p>
        </div>
        <EquipmentForm :public-tips="publicTips" />
    </div>
</template>
