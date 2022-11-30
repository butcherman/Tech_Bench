<template>
    <Overlay :loading="loading">
        <nav>
            <div
                :key="loadKey"
                id="nav-tab"
                class="nav nav-tabs"
            >
                <button
                    v-for="(equipType, index) in equip"
                    :key="equipType.cust_equip_id"
                    :id="`nav-${equipType.cust_equip_id}-tab`"
                    class="nav-link"
                    :class="{ active : index === 0 }"
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
        <div
            :key="loadKey"
            id="nav-tabContent"
            class="tab-content"
        >
            <div
                v-for="(equipData, index) in equip"
                :key="equipData.cust_equip_id"
                :id="`nav-${equipData.cust_equip_id}`"
                class="tab-pane fade"
                :class="{ 'active show' : index === 0 }"
                show
                active
            >
                <table class="table">
                    <tbody>
                        <tr
                            v-for="data in equipData.customer_equipment_data"
                            :key="data.id"
                        >
                            <th class="text-end">{{ data.field_name }}:</th>
                            <td>{{ data.value }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                <button
                                    class="btn btn-danger mx-1 float-end"
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
    </Overlay>
</template>

<script setup lang="ts">
    import Overlay from '@/Components/Base/Overlay.vue';
    import EditEquipment from '@/Components/Customer/Equipment/EditEquipment.vue';
    import { ref, reactive, onMounted, inject } from 'vue';
    import { verifyModal } from '@/Modules/verifyModal.module';
    import { Inertia } from '@inertiajs/inertia';
    import type { customerEquipmentType } from '@/Types';

    const loading = ref<boolean>(false);
    const loadKey = ref<number>(0);
    const equip   = inject<customerEquipmentType[]>('equipment');

    const deleteEquipment = (equip:customerEquipmentType) => {
        let msg = 'All information for this Equipment will also be deleted';
        if(equip.shared)
        {
            msg = 'This equipment is shared by multiple sites.  Deleting it will result in it being removed from all sites.';
        }

        verifyModal(msg).then(res => {
            if(res)
            {
                loading.value = true;
                Inertia.delete(route('customers.equipment.destroy', equip.cust_equip_id), {
                    only          : ['flash', 'equipment'],
                    preserveScroll: true,
                    onFinish      : () => {
                        loading.value = false;
                        loadKey.value++;
                    },
                });
            }
        });
    }
</script>

<style scoped lang="scss">
    .nav-link.active {
        background-color: rgb(227, 241, 241);
    }
</style>
