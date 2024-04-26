<template>
    <span>
        Any equipment assigned to the customer will be shown here. To see
        additional information about this equipment, select the equipment name.
        To attach a new piece of equipment to this customer, select
        <AddButton text="Add Equipment" small pill />
        <CollapseButton><customerEquipmentForm /></CollapseButton>
    </span>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import CollapseButton from "@/Components/_Base/Buttons/CollapseButton.vue";
import customerEquipmentForm from "../Forms/customer.customerEquipmentForm.vue";
</script>
