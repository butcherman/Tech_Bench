<template>
    <div>
        <h4 class="text-center font-bold">Hello World</h4>
        <v-card elevation="2" subtitle="This is a Title">
            <p class="text-center">This is a new page</p>
        </v-card>
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/Init/InitLayout.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
