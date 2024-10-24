<template>
    <div>
        <h5 class="text-center">Create Data Type</h5>
        <p class="text-center">
            Use this form to edit an existing Data Type Field for Equipment.
        </p>
        <p class="text-center">
            Modifying the REGEX Pattern will not affect any of the existing
            data, but the pattern will be enforced if the data is modified.
        </p>
        <equipmentDataTypeForm />
    </div>
</template>

<script setup lang="ts">
import equipmentDataTypeForm from "../Forms/equipment.dataTypeForm.vue";
</script>
