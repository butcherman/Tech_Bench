import { ref } from "vue";

export const workbookData = ref<workbookWrapper>({
    header: [
        {
            index: 0,
            type: "text",
            tag: "h1",
            text: "Header 1",
            class: "text-center",
        },
        {
            index: 1,
            type: "static",
            tag: "h3",
            text: "[ Customer Name ]",
            class: "text-center",
        },
    ],
    body: [
        {
            page: "0",
            title: "Page 1",
            canPublish: true,
            container: [
                {
                    index: 2,
                    type: "wrapper",
                    tag: "div",
                    class: "grid grid-cols-2 gap-2",
                    container: [
                        {
                            index: 3,
                            type: "wrapper",
                            tag: "fieldset",
                            class: "border border-slate-300 rounded-lg p-2",
                            container: [
                                {
                                    index: 4,
                                    type: "text",
                                    tag: "legend",
                                    text: "form legend",
                                },
                                {
                                    index: 5,
                                    tag: "input",
                                    class: "w-full border border-slate-300",
                                    props: {
                                        name: "test_input",
                                        label: "This is a test input",
                                        placeholder: null,
                                        value: null,
                                    },
                                },
                                {
                                    index: 6,
                                    tag: "input",
                                    class: "w-full border border-slate-300",
                                    props: {
                                        name: "test_input",
                                        label: "This is a test input",
                                        placeholder: null,
                                        value: null,
                                    },
                                },
                            ],
                        },
                        {
                            index: 7,
                            type: "wrapper",
                            tag: "fieldset",
                            class: "border border-slate-300 rounded-lg",
                            container: [
                                {
                                    index: 8,
                                    tag: "input",
                                    class: "w-full border border-slate-400 rounded-lg px-2",
                                    props: {
                                        name: "test_input",
                                        label: "This is a test input",
                                        placeholder: null,
                                        value: null,
                                    },
                                },
                            ],
                        },
                    ],
                },
            ],
        },
        {
            page: "1",
            title: "Page 2",
            canPublish: true,
            data: [],
        },
    ],
    footer: [],
});
