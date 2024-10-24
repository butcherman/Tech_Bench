<template>
    <div class="text-center">
        <h5>Equipment Categories and Types</h5>
        <p>
            Anything that can be installed at a customer site should be listed
            here.
        </p>
        <p>
            Equipment can be assigned to customers to keep track of information
            for the customer, for each piece of equipment that they have
            installed.
        </p>
        <p>
            Equipment can also be tagged when creating Tech Tips to assist users
            in searching through the Tech Tips.
        </p>
        <p>
            Equipment Categories are ways to group different equipment together.
            Categories can be used to best fit your business model. They can be
            as specific as a manufacturer name, or as broad as needed. Equipment
            types will be placed under each Category.
        </p>
        <p>
            Use the
            <AddButton text="Create New Equipment Category" small /> button to
            add a Category. Use the <EditBadge /> button to modify a Category
            name. Or use the <DeleteBadge /> button to remove an empty Category.
        </p>
        <p>
            <strong>Note:</strong> You can only remove a category if it has no
            Equipment assigned to it.
        </p>
        <p>
            Use the <AddButton text="Create New Equipment" small /> button to
            create a new Equipment Type.
        </p>
    </div>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
</script>
