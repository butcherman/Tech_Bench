<template>
    <div v-if="equipment.length === 0">
        <h5 class="text-center">No Equipment Assigned to this Customer</h5>
    </div>
    <div v-else class="accordion accordion-flush" id="equipment-accordion">
        <div
            v-for="(equip, index) in equipment"
            :key="equip.equip_id"
            class="accordion-item"
        >
            <h2 class="accordion-header">
                <button
                    class="accordion-button"
                    :class="{ collapsed: index !== 0 }"
                    type="button"
                    data-bs-toggle="collapse"
                    :data-bs-target="`#collapse-${equip.equip_id}`"
                >
                    <span
                        v-if="equip.shared"
                        class="me-2"
                        title="Shared between sites"
                        v-tooltip
                    >
                        <fa-icon icon="fa-share" />
                    </span>
                    {{ equip.name }}
                </button>
            </h2>
            <div
                :id="`collapse-${equip.equip_id}`"
                class="accordion-collapse collapse"
                :class="{ show: index === 0 }"
                data-bs-parent="#equipment-accordion"
            >
                <table class="table table-sm">
                    <tbody>
                        <tr
                            v-for="data in equip.customer_equipment_data"
                            :key="data.id.toString()"
                        >
                            <th class="text-end">{{ data.field_name }}:</th>
                            <td style="min-width: 50%">{{ data.value }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-center">
                                <DeleteButton
                                    class="btn-sm"
                                    @click="deleteEquipment(equip)"
                                />
                                <EditEquipment
                                    v-if="permission.equipment.update"
                                    :equip-data="equip"
                                />
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import EditEquipment from "@/Components/Customer/Equipment/EditEquipment.vue";
import DeleteButton from "@/Components/Base/Buttons/DeleteButton.vue";
import { ref, inject } from "vue";
import { verifyModal } from "@/Modules/verifyModal.module";
import { router } from "@inertiajs/vue3";
import {
    custPermissionsKey,
    toggleEquipLoadKey,
} from "@/SymbolKeys/CustomerKeys";

defineProps<{
    equipment: customerEquipment[];
}>();

const loadKey = ref<number>(0);
const permission = inject(custPermissionsKey) as customerPermissions;
const toggleLoad = inject(toggleEquipLoadKey) as () => void;

/**
 * Delete equipment from customer
 */
const deleteEquipment = (equip: customerEquipment) => {
    let msg = "All information for this Equipment will also be deleted";
    if (equip.shared) {
        msg =
            "This equipment is shared by multiple sites.  Deleting it will result in it being removed from all sites.";
    }

    verifyModal(msg).then((res) => {
        if (res) {
            toggleLoad();
            router.delete(
                route("customers.equipment.destroy", equip.cust_equip_id),
                {
                    only: ["flash", "equipment"],
                    preserveScroll: true,
                    onFinish: () => {
                        toggleLoad();
                        loadKey.value++;
                    },
                }
            );
        }
    });
};
</script>

<style scoped lang="scss">
h2 button {
    font-weight: bold;
    background-color: #619ced;
}

table {
    width: 100%;
    tbody {
        width: 100%;
        tr {
            width: 100%;
            th {
                width: 40%;
                max-width: 50%;
            }
        }
    }
}
</style>
