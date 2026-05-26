<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name', 'Tech Bench') }}</title>
    <style>
        /** Template Styles */
        .logo-image {
            height: 3rem;
        }

        .pdf-header {
            border-bottom: 1px solid #000000;
            width: 100%;
        }

        /** Text Styles */
        .text-center {
            text-align: center;
        }

        .text-faded {
            color: #999999
        }

        /** Height and Width Styles */
        .full-width {
            width: 100%;
        }

        .three-quarter-width {
            width: 75%;
        }

        .half-width {
            width: 50%;
        }

        .dime-width {
            width: 10%;
        }

        /** Positioning Styles */
        .center {
            margin-left: auto;
            margin-right: auto;
        }

        .float-end {
            float: right;
            margin-left: 2px;
        }

        .float-start {
            float: left;
            margin-right: 2px;
        }

        .clear-fix {
            clear: both;
        }

        /** Border Styles */
        .light-border-bottom {
            border-bottom: 1px solid #999999
        }

        .light-border-top {
            border-top: 1px solid #999999
        }
    </style>
</head>

<body>
    <div>
        <table class="pdf-header">
            <tbody>
                <tr>
                    <td class="dime-width">
                        <img src="{{ public_path(config('app.logo')) }}" alt="Company Logo" class="logo-image" />
                    </td>
                    <td>
                        <h1 class="text-center">
                            {{ config('app.name', 'Tech Bench') }}
                            @hasSection('title')
                                -
                                @yield('title')
                            @endif
                        </h1>
                    </td>
                    <td class="dime-width"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        @yield('content')
    </div>
</body>

</html>