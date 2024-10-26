<template>
    <div class="table-responsive">
        <table class="table">
            <tbody>
                <tr>
                    <th class="text-end">Name:</th>
                    <td>Enter a descriptive name for this Data Field</td>
                </tr>
                <tr>
                    <th class="text-end">REGEX Pattern:</th>
                    <td>
                        To add validation to this field, you can enter a Regular
                        Expression sequence. For more information on REGEX and
                        testing help, check out an online REGEX tester like
                        <a href="https://regex101.com" target="_blank">
                            Regex 101
                        </a>
                    </td>
                </tr>
                <tr>
                    <th class="text-end">Field is Masked</th>
                    <td>
                        When enabled, the field will not show its contents
                        unless directly interacted with (hover or click show
                        icon). This is helpful for password fields and other
                        sensitive information.
                    </td>
                </tr>
                <tr>
                    <th class="text-end">Field is Hyperlink:</th>
                    <td>
                        When enabled, the field will be treated as a link to an
                        external website.
                    </td>
                </tr>
                <tr>
                    <th class="text-end">Add A Copy to Clipboard Button</th>
                    <td>
                        When enabled, the field will have a
                        <span class="badge bg-warning rounded-pill">
                            <fa-icon icon="copy" />
                        </span>
                        button next to the field to allow the user to quickly
                        add the field to their clipboard.
                    </td>
                </tr>
            </tbody>
        </table>
        >
    </div>
</template>
