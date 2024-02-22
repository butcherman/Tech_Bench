<template>
    <AddButton pill small @click="addEquipmentModal?.show">
        Add Equipment
        <Modal ref="addEquipmentModal" title="Add Customer Equipment">
            dd
        </Modal>
    </AddButton>
</template>

<script setup lang="ts">
import AddButton from "../_Base/Buttons/AddButton.vue";
import Modal from "../_Base/Modal.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();

const addEquipmentModal = ref<InstanceType<typeof Modal> | null>(null);
</script>
