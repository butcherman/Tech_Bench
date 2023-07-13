<template>
    <div>
        <Head title="Equipment Data Types" />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Equipment Data Types</div>
                        <p class="text-center">
                            When equipment is assigned to a customer, the
                            following Data Types are available to gather
                            information for that equipment.
                        </p>
                        <p class="text-center">
                            <strong>Note:</strong> Data Types that are in use
                            cannot be deleted until they have been removed from
                            all Equipment Types
                        </p>
                        <hr />
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>
                                            Pattern
                                            <span
                                                title="What is this?"
                                                class="pointer text-info"
                                                @click="showHelp('pattern')"
                                                v-tooltip
                                            >
                                                <fa-icon
                                                    icon="circle-question"
                                                />
                                            </span>
                                        </th>
                                        <th>
                                            Masked
                                            <span
                                                title="What is this?"
                                                class="pointer text-info"
                                                @click="showHelp('masked')"
                                                v-tooltip
                                            >
                                                <fa-icon
                                                    icon="circle-question"
                                                />
                                            </span>
                                        </th>
                                        <th>
                                            Required
                                            <span
                                                title="What is this?"
                                                class="pointer text-info"
                                                @click="showHelp('required')"
                                                v-tooltip
                                            >
                                                <fa-icon
                                                    icon="circle-question"
                                                />
                                            </span>
                                        </th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template
                                        v-for="item in dataTypes"
                                        :key="item.type_id"
                                    >
                                        <tr>
                                            <td>{{ item.name }}</td>
                                            <td class="overflow-hidden">
                                                {{
                                                    item.pattern
                                                        ? `Assigned`
                                                        : `none`
                                                }}
                                            </td>
                                            <td>
                                                <fa-icon
                                                    :icon="
                                                        item.masked
                                                            ? `check`
                                                            : `xmark`
                                                    "
                                                    :class="
                                                        item.masked
                                                            ? `text-success`
                                                            : `text-danger`
                                                    "
                                                />
                                            </td>
                                            <td>
                                                <fa-icon
                                                    :icon="
                                                        item.required
                                                            ? `check`
                                                            : `xmark`
                                                    "
                                                    :class="
                                                        item.required
                                                            ? `text-success`
                                                            : `text-danger`
                                                    "
                                                />
                                            </td>
                                            <td>
                                                <Link :href="$route('data-types.edit', item.type_id)">
                                                    <EditBadge />
                                                </Link>
                                                <DeleteBadge v-if="!item.in_use" @click="deleteType(item.type_id)" />
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <Link :href="$route('data-types.create')">
                            <AddButton class="w-75">Create New Data Type</AddButton>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import okModal from "@/Modules/ok";
import verify from "@/Modules/verify";
import { router } from "@inertiajs/vue3";

defineProps<{
    dataTypes: dataTypes[];
}>();

const showHelp = (type: string) => {
    let msg = "";

    switch (type) {
        case "pattern":
            msg =
                "When a REGEX pattern is given, the entry for this field must match the provided REGEX pattern";
            break;
        case "masked":
            msg =
                "When enabled, this field will not display by default.  To view it must be manually activated.  Good for password fields and other sensitive information";
            break;
        case "required":
            msg =
                "When enabled, an error will show if no data was presented for this field";
            break;
    }

    okModal(msg);
};

const deleteType = (typeId: number) => {
    verify({message: 'This cannot be undone'}).then(res => {
        if(res) {
            router.delete(route('data-types.destroy', typeId));
        }
    })
}
</script>

<script lang="ts">
export default { layout: AppLayout, components: { AddButton } };
</script>
