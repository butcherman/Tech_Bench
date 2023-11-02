<template>
    <span
        v-if="phoneLines.length"
        class="float-end text-primary mx-2 pointer"
        title="Call"
        v-tooltip
        @click="openCallModal"
    >
        <fa-icon icon="phone-flip" />
        <Modal ref="callListModal" title="Select Number to Call">
            <ul class="list-group">
                <li
                    v-for="num in phoneLines"
                    :key="num.cont_id"
                    class="list-group-item text-center"
                >
                    <a
                        :href="getTelLink(num)"
                        :title="num.phone_number_type.description"
                        v-tooltip
                    >
                        <fa-icon
                            :icon="num.phone_number_type.icon_class"
                            class="mx-2"
                        />
                        {{ num.formatted }}
                        <span v-if="num.extension">
                            Ext: {{ num.extension }}
                        </span>
                    </a>
                </li>
            </ul>
        </Modal>
    </span>
</template>

<script setup lang="ts">
import Modal from "../Modal.vue";
import { ref } from "vue";

const props = defineProps<{
    phoneLines: contactPhone[];
}>();

const callListModal = ref<InstanceType<typeof Modal> | null>(null);

const openCallModal = () => {
    if (props.phoneLines.length > 1) {
        callListModal.value?.show();
    } else {
        window.location.href = getTelLink(props.phoneLines[0]);
    }
};

const getTelLink = (phoneLine: contactPhone) => {
    let num = `tel:${phoneLine.phone_number.toString()}`;

    if (phoneLine.extension) {
        num += `,,${phoneLine.extension}`;
    }

    return num;
};
</script>
