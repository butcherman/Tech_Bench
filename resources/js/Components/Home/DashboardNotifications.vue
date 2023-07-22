<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Notifications
                <span class="badge text-bg-warning rounded-pill float-end">
                    {{ newNotificationCount }}
                    /
                    {{ notificationList.length }}
                </span>
            </div>
            <div id="notification-list">
                <Overlay :loading="loadingState">
                    <table class="table table-sm notification-table">
                        <thead>
                            <tr>
                                <th>
                                    <input
                                        id="check-all"
                                        ref="checkAllMaster"
                                        class="form-check-input"
                                        type="checkbox"
                                        value="check-all"
                                        v-model="allCheck"
                                        @change="checkAll"
                                    />
                                </th>
                                <th>Subject:</th>
                                <th>Date:</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <tr v-if="!notificationList.length">
                                <td colspan="3" class="text-center">
                                    No Notifications
                                </td>
                            </tr>
                            <tr
                                v-for="notification in notificationList"
                                :key="notification.id"
                            >
                                <td>
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        v-model="selected"
                                        :value="notification.id"
                                        @change="intermediateCheck"
                                    />
                                </td>
                                <td
                                    :class="
                                        notification.read_at
                                            ? 'fw-light'
                                            : 'fw-bold'
                                    "
                                    class="pointer"
                                >
                                    {{ notification.data.subject }}
                                </td>
                                <td
                                    :class="
                                        notification.read_at
                                            ? 'fw-light'
                                            : 'fw-bold'
                                    "
                                    class="pointer"
                                >
                                    {{ notification.created_at }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </Overlay>
            </div>
            <div v-if="selected.length">
                <button
                    class="btn btn-info m-2"
                    @click="processSelected('mark')"
                >
                    Mark As Read
                </button>
                <button class="btn btn-danger m-2" @click="processSelected('delete')">
                    Delete
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import { ref } from "vue";
import { newNotificationCount, notificationList, sendNotificationUpdate, loadingState } from '@/State/NotificationState';
import verify from "@/Modules/verify";


const checkAllMaster = ref<InstanceType<typeof HTMLInputElement> | null>(null);

const allCheck = ref<boolean>(false);
const selected = ref<string[]>([]);
// const notificationList = computed(() => notificationList);

/**
 * Check/Uncheck all items
 */
const checkAll = (): void => {
    if (allCheck.value) {
        notificationList.value.forEach((notification: notification) => {
            selected.value.push(notification.id);
        });
    } else {
        selected.value = [];
    }
};

const clearCheckAll = () => {
    selected.value = [];
    allCheck.value = false;
    if (checkAllMaster.value !== null) {
        checkAllMaster.value.indeterminate = false;
    }
}

/**
 * For styling, trigger the indeterminate pseudo class on the input box
 */
const intermediateCheck = (): void => {
    allCheck.value = false;
    if (checkAllMaster.value !== null) {
        checkAllMaster.value.indeterminate = true;
    }
};

/**
 * Delete or Mark a group of notifications
 */
const processSelected = (type: 'mark' | 'delete') => {
    if(type === 'mark') {
        sendNotificationUpdate('mark', selected.value);
        clearCheckAll();
    } else {
        verify({message: 'This cannot be undone'}).then((res) => {
            if(res) {
                sendNotificationUpdate('delete', selected.value);
                clearCheckAll();
            }
        });
    }
}
</script>

<style scoped lang="scss">
#notification-list {
    height: 250px;
    overflow-y: auto;
    thead tr {
        position: sticky;
        top: 0;
    }
    tbody tr:hover {
        background-color: #b2b6b4;
    }
}
</style>
