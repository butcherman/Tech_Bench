<template>
    <div class="table-responsive">
        <table class="table">
            <tbody>
                <tr>
                    <th class="text-end">Parent Customer Name:</th>
                    <td>
                        When selected a new pop-up will show for searching for
                        the customer this site is to be created under
                    </td>
                </tr>
                <tr>
                    <th class="text-end">Site Name:</th>
                    <td>The name to reference the site by.</td>
                </tr>
                <tr>
                    <th class="text-end">Address:</th>
                    <td>Site physical address.</td>
                </tr>
                <tr>
                    <th class="text-end">City:</th>
                    <td>City site is located in.</td>
                </tr>
                <tr>
                    <th class="text-end">State:</th>
                    <td>State site is located in.</td>
                </tr>
                <tr>
                    <th class="text-end">Zip Code:</th>
                    <td>Zip Code site is located in.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
