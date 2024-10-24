<template>
    <div class="text-center">
        <h5>Deleted Items</h5>
        <p>
            Equipment, Contacts, Notes, and Files that have been deleted will go
            to this page and can be recovered for up to 90 days (if auto purge
            is enabled).
        </p>
        <p>
            From this page an administrator can restore or remove a deleted
            item.
        </p>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
