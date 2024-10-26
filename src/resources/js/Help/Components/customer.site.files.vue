<template>
    <li class="list-group-item">
        <strong>Files</strong> - All uploaded files will be listed here. To
        download a file, click on the name or click on
        <AddButton text="Add File" small pill /> to upload a new file.
        <CollapseButton><customerFileForm /></CollapseButton>
    </li>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import CollapseButton from "@/Components/_Base/Buttons/CollapseButton.vue";
import customerFileForm from "../Forms/customer.customerFileForm.vue";
</script>
