<template>
    <nav>
        <div :key="loadKey" id="nav-tab" class="nav nav-tabs">
            <button
                v-for="(equipType, index) in equipment"
                :key="equipType.cust_equip_id"
                :id="`nav-${equipType.cust_equip_id}-tab`"
                class="nav-link"
                :class="{ active: index === 0 }"
                data-bs-toggle="tab"
                :data-bs-target="`#nav-${equipType.cust_equip_id}`"
                type="button"
            >
                <span
                    v-if="equipType.shared"
                    title="Equipment Shared Across Sites"
                    v-tooltip
                >
                    <fa-icon icon="fa-share-nodes" />
                </span>
                {{ equipType.name }}
            </button>
        </div>
    </nav>
    <div :key="loadKey" id="nav-tabContent" class="tab-content">
        <div
            v-for="(equipData, index) in equipment"
            :key="equipData.cust_equip_id"
            :id="`nav-${equipData.cust_equip_id}`"
            class="tab-pane fade"
            :class="{ 'active show': index === 0 }"
            show
            active
        >
            <table class="table table-smd">
                <tbody>
                    <tr
                        v-for="data in equipData.customer_equipment_data"
                        :key="data.id.toString()"
                    >
                        <th class="text-end">{{ data.field_name }}:</th>
                        <td style="min-width: 50%">{{ data.value }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center">
                            <button
                                v-if="permission?.equipment.delete"
                                class="btn btn-danger mx-1"
                                title="Delete Equipment"
                                v-tooltip
                                @click="deleteEquipment(equipData)"
                            >
                                <fa-icon icon="fa-trash-can" />
                                Delete
                            </button>
                            <EditEquipment :equip-data="equipData" />
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import EditEquipment from "@/Components/Customer/Equipment/EditEquipment.vue";
import { ref, inject } from "vue";
import { verifyModal } from "@/Modules/verifyModal.module";
import { router } from "@inertiajs/vue3";
import {
    custPermissionsKey,
    toggleEquipLoadKey,
} from "@/SymbolKeys/CustomerKeys";
import type {
    customerEquipmentType,
    customerPermissionType,
    voidFunctionType,
} from "@/Types";

const props = defineProps<{
    equipment: customerEquipmentType[];
}>();

const loadKey = ref<number>(0);
const permission = inject(custPermissionsKey) as customerPermissionType;
const toggleLoad = inject(toggleEquipLoadKey) as voidFunctionType;

/**
 * Delete equipment from customer
 */
const deleteEquipment = (equip: customerEquipmentType) => {
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
.nav-link.active {
    background-color: rgb(227, 241, 241);
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
