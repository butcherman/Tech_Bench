<template>
    <div>
        <h5 class="text-center">Create New Equipment</h5>
        <p class="text-center">
            Use this page to create a new equipment type. The Equipment Type can
            be assigned to customers and be used to track information for that
            Equipment. It can also be assigned to Tech Tips to assist others
            with helpful information about the Equipment.
        </p>
        <equipmentEquipmentForm />
    </div>
</template>

<script setup lang="ts">
import equipmentEquipmentForm from "../Forms/equipment.equipmentForm.vue";
</script>
