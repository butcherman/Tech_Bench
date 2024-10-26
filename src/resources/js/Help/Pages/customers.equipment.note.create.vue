<template>
    <div>
        <h5 class="text-center">Create Equipment Note</h5>
        <p class="text-center">
            Use this page to create a new Customer Note that will be attached to
            equipment.
        </p>
        <customerNoteForm />
    </div>
</template>

<script setup lang="ts">
import customerNoteForm from "../Forms/customer.customerNoteForm.vue";
</script>
