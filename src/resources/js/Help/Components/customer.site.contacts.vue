<template>
    <li class="list-group-item">
        <strong>Contacts</strong> - All contacts associated with this customer
        will be listed here. To create a new contact select
        <AddButton text="Add Contact" small pill />.
        <CollapseButton> <customerContactForm /> </CollapseButton>
    </li>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import CollapseButton from "@/Components/_Base/Buttons/CollapseButton.vue";
import customerContactForm from "../Forms/customer.customerContactForm.vue";
</script>
