<template>
    <div id="customer-wrapper">
        <Head :title="customer.name" />
        <div class="border-bottom border-secondary-subtle mb-2">
            <CustomerDetails />
        </div>
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-center">{{ equipment.equip_name }}</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr
                                        v-for="data in sortDataObject(
                                            equipmentData,
                                            'asc',
                                            'order'
                                        )"
                                        :key="data.id"
                                    >
                                        <th class="text-end">
                                            {{ data.field_name }}:
                                        </th>
                                        <td
                                            :class="{
                                                'mask-field':
                                                    data.data_field_type.masked,
                                            }"
                                        >
                                            <span class="mask-text">
                                                <fa-icon icon="ellipsis" />
                                                <fa-icon icon="ellipsis" />
                                                <fa-icon icon="ellipsis" />
                                                <fa-icon icon="ellipsis" />
                                                <fa-icon icon="ellipsis" />
                                            </span>
                                            <span class="mask-value">
                                                <a
                                                    v-if="
                                                        data.data_field_type
                                                            .is_hyperlink
                                                    "
                                                    :href="
                                                        checkForHyperlink(
                                                            data.value
                                                        )
                                                    "
                                                    target="_blank"
                                                    >{{ data.value }}</a
                                                >
                                                <span
                                                    v-else
                                                    style="
                                                        -webkit-text-security: disk;
                                                    "
                                                >
                                                    {{ data.value }}
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <ClipboardCopy
                                                v-if="
                                                    data.data_field_type
                                                        .allow_copy
                                                "
                                                :value="data.value"
                                                class="float-end"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-center text-small">
                                Hover over or tap on masked fields to show their
                                value
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/CustomerDetails.vue";
import ClipboardCopy from "@/Components/_Base/Badges/ClipboardCopy.vue";
import { ref, reactive, onMounted } from "vue";
import { customer } from "@/State/CustomerState";
import { sortDataObject } from "@/Modules/SortDataObject.module";

const props = defineProps<{
    equipment: customerEquipment;
    equipmentData: customerEquipmentData[];
}>();

const checkForHyperlink = (url: string): string => {
    if (!url.match("^(http|https)://")) {
        return "https://" + url;
    }

    return url;
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<style scoped lang="scss">
.mask-text {
    display: none;
}
.mask-field {
    .mask-text {
        display: inline;
    }
    .mask-value {
        display: none;
    }

    &:hover {
        .mask-text {
            display: none;
        }
        .mask-value {
            display: inline;
        }
    }
}
</style>
