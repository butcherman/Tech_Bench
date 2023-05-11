<template>
    <EditButton class="float-end" @click="editNoteModal.show()" />
    <Modal ref="editNoteModal" title="Edit Note" size="xl">
        <NoteForm :note-data="note" @success="editNoteModal.hide()" />
    </Modal>
</template>

<script setup lang="ts">
    import EditButton from '@/Components/Base/Buttons/EditButton.vue';
    import Modal from '@/Components/Base/Modal/Modal.vue';
    import NoteForm from '@/Forms/Customer/NoteForm.vue';
    import { ref, reactive, onMounted } from 'vue';
    import type { customerNoteType, customerType } from '@/Types';

    const props = defineProps<{
        note: customerNoteType;
    }>();

    const editNoteModal = ref<InstanceType<typeof Modal> | null>(null);
</script>
