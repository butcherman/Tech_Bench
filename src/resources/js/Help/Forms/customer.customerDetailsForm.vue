<template>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
                <tr v-if="selectId">
                    <th class="text-end">Customer ID:</th>
                    <td>
                        Enter a unique identifier for this customer. This can be
                        used to match other databases such as billing database.
                        <br />
                        Leave blank to have the system auto assign the ID.
                    </td>
                </tr>
                <tr>
                    <th class="text-end">Customer Name:</th>
                    <td>Enter the name of the customer</td>
                </tr>
                <tr>
                    <th class="text-end">Secondary Name (AKA Name)</th>
                    <td>
                        If the customer goes by more than one name, enter
                        additional names here. This field can be used to hold
                        additional strings to search for this user.
                    </td>
                </tr>
                <tr v-if="!edit">
                    <th class="text-end">Address:</th>
                    <td>Customers physical address.</td>
                </tr>
                <tr v-if="!edit">
                    <th class="text-end">City:</th>
                    <td>City customer is located in.</td>
                </tr>
                <tr v-if="!edit">
                    <th class="text-end">State:</th>
                    <td>State customer is located in.</td>
                </tr>
                <tr v-if="!edit">
                    <th class="text-end">Zip Code:</th>
                    <td>Zip Code customer is located in.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    edit?: boolean;
    selectId?: boolean;
}>();
</script>
