<template>
    <div>
        <h5 class="text-center">Customer Alerts</h5>
        <p class="text-center">
            Customer Alerts are displayed on all pages for this customer. They
            will be a large alert below the customer name and above all customer
            information.
        </p>
        <p class="text-center">
            Alerts should be used to convey important information about the
            customer that should not be overlooked when a user goes to the
            customers information pages.
        </p>
        <p class="text-center">
            Select <AddButton pill small text="Create Alert" /> to create a new
            alert.
        </p>
        <p class="text-center">
            Select the <EditBadge /> button to modify an existing alert, or the
            <DeleteBadge />
            button to remove the alert.
        </p>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th class="text-end">Alert Message:</th>
                    <td>
                        This is the message that should display in the alert
                    </td>
                </tr>
                <tr>
                    <th class="text-end">Alert Type:</th>
                    <td>
                        This determines the background color of the alert.
                        <div>
                            <div class="alert alert-danger text-center">
                                <fa-icon
                                    icon="exclamation-circle"
                                    class="float-start"
                                />
                                Sample Alert
                            </div>
                            <div class="alert alert-warning text-center">
                                <fa-icon
                                    icon="triangle-exclamation"
                                    class="float-start"
                                />
                                Sample Warning
                            </div>
                            <div class="alert alert-success text-center">
                                <fa-icon
                                    icon="circle-check"
                                    class="float-start"
                                />
                                Sample General Note
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
</script>
