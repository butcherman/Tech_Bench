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
                    <strong>Field is Masked</strong>
                    When enabled, the field will not show its contents unless
                    directly interacted with (hover or click show icon). This is
                    helpful for password fields and other sensitive information.
                </li>
                <li class="list-group-item">
                    <strong>Field is a Hyperlink</strong>
                    When enabled, the field will be treated as a link to an
                    external website.
                </li>
                <li class="list-group-item">
                    <strong>Add A Copy to Clipboard Button</strong>
                    When enabled, the field will have a
                    <span class="badge bg-warning rounded-pill">
                        <fa-icon icon="copy" />
                    </span>
                    button next to the field to allow the user to quickly add
                    the field to their clipboard.
                </li>
            </ul>
        </div>
    </div>
</template>
