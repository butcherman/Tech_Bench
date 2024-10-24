<template>
    <div class="table-responsive">
        <table class="table">
            <tbody>
                <tr>
                    <th class="text-end">Equipment Category:</th>
                    <td>
                        Select one of the pre-defined Categories to assign this
                        Equipment under.
                    </td>
                </tr>
                <tr>
                    <th class="text-end">Equipment Name:</th>
                    <td>
                        Enter a descriptive name to assign to this Equipment
                    </td>
                </tr>
                <tr>
                    <th class="text-end">Customer Information to Gather</th>
                    <td>
                        Input one or more fields for information that needs to
                        be gathered for each customer that this Equipment is
                        assigned to. For example:
                        <ul>
                            <li>Login Username</li>
                            <li>Login Password</li>
                            <li>Software Version</li>
                        </ul>
                        Add additional rows by using the <AddButton small /> to
                        create a new blank row. Existing fields (Data Types) are
                        available to be selected, or you can create a new field
                        by just typing in its name.
                        <br />
                        <span class="text-danger">
                            <strong>Important Note:</strong> If you delete a
                            field for existing equipment and submit the update,
                            all information collected under that field will be
                            lost! Be careful when removing fields to ensure you
                            do not lose any important data!
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
</script>
