<template>
    <div>
        <h5 class="text-center">Contact Phone Types</h5>
        <p class="text-center">
            When a Contact is created with a phone number, the user must
            identify the phone type.
        </p>
        <p class="text-center">Default Phone Types are:</p>
        <div class="d-flex justify-content-center">
            <ul>
                <li>Home</li>
                <li>Work</li>
                <li>Mobile</li>
            </ul>
        </div>
        <p class="text-center">You can add and remove Phone Types as needed.</p>
        <p class="text-center">
            <strong>Note:</strong> You cannot remove a Phone Type if it is in
            use.
        </p>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
