<template>
    <AddButton
        class="float-end"
        pill
        small
        title="Add Note"
        v-tooltip
        @click="newNoteModal?.show()"
    />
    <Modal ref="newNoteModal" title="New Note" size="xl">
        <NoteForm @success="newNoteModal?.hide()" />
    </Modal>
</template>

<script setup lang="ts">
import AddButton from "@/Components/Base/Buttons/AddButton.vue";
import Modal from "@/Components/Base/Modal/Modal.vue";
import NoteForm from "@/Forms/Customer/NoteForm.vue";
import { ref } from "vue";

const newNoteModal = ref<InstanceType<typeof Modal> | null>(null);
</script>
