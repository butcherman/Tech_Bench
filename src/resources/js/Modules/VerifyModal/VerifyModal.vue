<template>
    <v-dialog v-model="isOpen" max-width="400" @after-leave="onHidden">
        <v-card>
            <v-card-title class="text-center">{{ title }}</v-card-title>
            <div class="p-4 text-center">{{ message }}</div>
            <v-card-actions>
                <v-btn border color="error" @click="noClicked">
                    <font-awesome-icon icon="xmark" class="mx-1" />
                    No
                </v-btn>
                <v-btn border color="success" @click="yesClicked">
                    <font-awesome-icon icon="check" class="mx-1" />
                    Yes
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref } from "vue";

const emit = defineEmits(["yes-clicked", "no-clicked", "hide", "hidden"]);
defineProps<{
    title: string;
    message: string;
}>();

const isOpen = ref(true);

function closeModal(): void {
    emit("hide");
    isOpen.value = false;
}

function yesClicked(): void {
    emit("yes-clicked");
    closeModal();
}

function noClicked(): void {
    emit("no-clicked");
    closeModal();
}

function onHidden(): void {
    emit("hidden");
}
</script>
