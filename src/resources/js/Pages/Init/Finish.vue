<template>
    <div>
        <div>
            <h6>Building Application...</h6>
            <div class="ms-4 mt-4">
                <div v-for="msg in setupMsg">{{ msg }}</div>
                <EllipsisLoader v-if="!isComplete" />
            </div>
        </div>
        <div v-if="isComplete">
            <hr />
            <h5 class="text-center">Setup Complete!</h5>
            <p class="text-center">
                You have completed the Wizard and are ready to get going. Click
                the link below to log in again and start using Tech Bench.
            </p>
            <a :href="`${newUrl}/dashboard`" class="btn btn-info w-100">
                Go To Login Page
            </a>
        </div>
    </div>
</template>

<script setup lang="ts">
import EllipsisLoader from "@/Components/_Base/Loaders/EllipsisLoader.vue";
import InitLayout from "@/Layouts/Init/InitLayout.vue";
import { ref } from "vue";

const isComplete = ref<boolean>(false);
const setupMsg = ref<string[]>([]);
const newUrl = ref<string | null>(null);
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
