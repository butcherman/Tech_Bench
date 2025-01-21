<template>
    <Card title="Additional Data:">
        <table class="table-auto">
            <tbody>
                <tr class="border">
                    <th class="text-start ps-1">REGEX Pattern:</th>
                    <td class="p-2">
                        To add validation to this field, you can enter a Regular
                        Expression sequence. For more information on REGEX and
                        testing help, check out an online REGEX tester like
                        <a href="https://regex101.com" target="_blank"
                            >Regex 101</a
                        >.
                    </td>
                </tr>
                <tr class="border">
                    <th class="text-start ps-1">
                        Error Message when Pattern is not Matched:
                    </th>
                    <td class="p-2">
                        Enter a descriptive message to convey to the user what
                        pattern they should be using for this Data Type.
                        <br />
                        <span class="text-danger">
                            <span class="font-bold">Important Note:</span> Even
                            if the pattern does not match the user will still be
                            able to save the invalid field. The pattern option
                            is there to let the user know that the data they are
                            inputting is not in the proper format.
                        </span>
                    </td>
                </tr>
                <tr class="border">
                    <th class="text-start ps-1">Field is Masked:</th>
                    <td class="p-2">
                        When enabled, the field will not show its contents
                        unless directly interacted with (hover or click show
                        icon). This is helpful for password fields and other
                        sensitive information.
                    </td>
                </tr>
                <tr class="border">
                    <th class="text-start ps-1">Field is a Hyperlink:</th>
                    <td class="p-2">
                        When enabled, the field will be treated as a link to an
                        external website.
                    </td>
                </tr>
                <tr class="border">
                    <th class="text-start ps-1">
                        Add A Copy to Clipboard Button:
                    </th>
                    <td class="p-2">
                        When enabled, the field will have a
                        <BaseBadge variant="warning" icon="copy" />
                        button next to the field to allow the user to quickly
                        add the field to their clipboard.
                    </td>
                </tr>
                <tr class="border">
                    <th class="text-start ps-1">Do Not Log Field Value:</th>
                    <td class="p-2">
                        When enabled the value of this field will be redacted in
                        system logs.
                    </td>
                </tr>
            </tbody>
        </table>
    </Card>
</template>

<script setup lang="ts">
import BaseBadge from "../_Base/Badges/BaseBadge.vue";
import Card from "../_Base/Card.vue";
</script>
