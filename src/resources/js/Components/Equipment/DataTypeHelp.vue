<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">Additional Information:</div>
            <ul class="list-group">
                <li class="list-group-item">
                    <strong>REGEX Pattern:</strong>
                    To add validation to this field, you can enter a Regular
                    Expression sequence. For more information on REGEX and
                    testing help, check out an online REGEX tester like
                    <a href="https://regex101.com" target="_blank">
                        Regex 101
                    </a>
                    .
                </li>
                <li class="list-group-item">
                    <strong>Error Message when Pattern is not Matched:</strong>
                    Enter a descriptive message to convey to the user what
                    pattern they should be using for this Data Type.
                    <br />
                    <span class="text-danger">
                        <strong>Important Note:</strong>Even if the pattern does
                        not match the user will still be able to save the
                        invalid field. The pattern option is there to let the
                        user know that the data they are inputting is not in the
                        proper format.
                    </span>
                </li>
                <li class="list-group-item">
                    <strong>Field is Masked:</strong>
                    When enabled, the field will not show its contents unless
                    directly interacted with (hover or click show icon). This is
                    helpful for password fields and other sensitive information.
                </li>
                <li class="list-group-item">
                    <strong>Field is a Hyperlink:</strong>
                    When enabled, the field will be treated as a link to an
                    external website.
                </li>
                <li class="list-group-item">
                    <strong>Add A Copy to Clipboard Button:</strong>
                    When enabled, the field will have a
                    <span class="badge bg-warning rounded-pill">
                        <fa-icon icon="copy" />
                    </span>
                    button next to the field to allow the user to quickly add
                    the field to their clipboard.
                </li>
                <li class="list-group-item">
                    <strong>Do Not Log Field Value:</strong>
                    When enabled the value of this field will be redacted in
                    system logs.
                </li>
            </ul>
        </div>
    </div>
</template>
