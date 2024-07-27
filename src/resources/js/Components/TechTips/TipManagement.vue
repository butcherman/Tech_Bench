<template>
    <div id="manage-tip-dropdown" class="dropdown">
        <button
            class="btn rounded-circle dropdown-toggle"
            type="button"
            data-bs-toggle="dropdown"
        >
            <fa-icon icon="ellipsis-vertical" />
        </button>
        <ul class="dropdown-menu">
            <li v-if="permissions.manage">
                <span class="dropdown-item pointer" @click="statsModal?.show">
                    Show Stats
                </span>
            </li>
            <li v-if="permissions.update">Edit Tip</li>
            <li v-if="permissions.delete">Delete Tip</li>
        </ul>
        <Modal ref="statsModal" title="Tech Tip Stats">
            <TableStacked :rows="statsTable" title-case />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import Modal from "../_Base/Modal.vue";
import TableStacked from "../_Base/TableStacked.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    tipData: techTip;
    permissions: basicPermissions;
}>();

const statsModal = ref<InstanceType<typeof Modal> | null>(null);
const statsTable = reactive({
    author: props.tipData.created_by.full_name,
    created_at: props.tipData.created_at,
    updated_at: props.tipData.updated_id ? props.tipData.updated_at : "N/A",
    updated_by: props.tipData.updated_id
        ? props.tipData.updated_by.full_name
        : "N/A",
    views: props.tipData.views,
    is_sticky: props.tipData.sticky,
    is_public: props.tipData.public,
});
</script>
