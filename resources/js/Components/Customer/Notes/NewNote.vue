<template>
    <button
        class="float-end btn btn-info btn-pill btn-sm"
        title="New Equipment"
        v-tooltip
        @click="newNoteModal?.show()"
    >
        <fa-icon icon="fa-plus" />
        Add
    </button>
    <Modal ref="newNoteModal" title="New Note" size="xl">
        <NoteForm @success="newNoteModal.hide()" />
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/Base/Modal/Modal.vue";
import NoteForm from "@/Forms/Customer/NoteForm.vue";
import { ref, reactive, onMounted } from "vue";

const newNoteModal = ref<InstanceType<typeof Modal> | null>(null);
</script>
