<template>
    <div class="table-responsive">
        <table class="table">
            <tbody>
                <tr v-for="data in equipmentData" :key="data.id">
                    <th class="text-end">{{ data.field_name }}:</th>
                    <td>
                        <input
                            v-if="editFields.includes(data.id)"
                            type="text"
                            :value="data.value"
                            class="form-control"
                        />
                        <span v-else>{{ data.value }}</span>
                    </td>
                    <td><EditBadge @click="editFields.push(data.id)" /></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">
                        <EditButton
                            class="float-end"
                            text="Edit All"
                            small
                            pill
                        />
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script setup lang="ts">
import EditButton from "../_Base/Buttons/EditButton.vue";
import EditBadge from "../_Base/Badges/EditBadge.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    equipmentData: customerEquipmentData[];
}>();

const editFields = ref<number[]>([]);
</script>
