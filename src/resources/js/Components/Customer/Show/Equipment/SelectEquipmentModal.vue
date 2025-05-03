<script setup lang="ts">
import Modal from "@/Components/_Base/Modal.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import { customer } from "@/Composables/Customer/CustomerData.module";
import { ref, useTemplateRef } from "vue";

const modal = useTemplateRef("select-equipment-modal");
const selectedEquip = ref<customerEquipment[]>();

/**
 * Open the Modal and show link options.
 */
const show = (equipment: customerEquipment[]): void => {
    selectedEquip.value = equipment;
    modal.value?.show();
};

defineExpose({ show });
</script>

<template>
    <Modal ref="select-equipment-modal" title="Select Equipment">
        <div>
            <ul class="border rounded-lg border-collapse">
                <li
                    v-for="equip in selectedEquip"
                    :key="equip.cust_equip_id"
                    class="my-2"
                >
                    <BaseButton
                        class="w-full"
                        :href="
                            $route('customers.equipment.show', [
                                customer.slug,
                                equip.cust_equip_id,
                            ])
                        "
                    >
                        <h5>{{ equip.equip_name }}</h5>
                        <ul>
                            <li v-for="site in equip.sites">
                                {{ site.site_name }},
                                {{ site.city }}
                            </li>
                        </ul>
                    </BaseButton>
                </li>
            </ul>
        </div>
    </Modal>
</template>
