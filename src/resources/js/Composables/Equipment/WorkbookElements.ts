import { computed } from "vue";

export const elementList = computed(() => [
    {
        type: "text",
        tag: "h1",
        text: "Header 1",
        class: "text-center",
        componentData: {
            label: "Header",
            help: "Header text for Workbook",
            buttonText: "H1",
        },
    },
    {
        type: "text",
        tag: "h3",
        text: "Sub-Header",
        class: "text-center",
        componentData: {
            label: "Sub Header",
            help: "Sub-Header text for Workbook",
            buttonText: "H3",
        },
    },
    // {
    //     index: 1,
    //     label: "SubHeader",
    //     help: "Subheader text for workbook",
    //     buttonText: "H3",
    //     data: {
    //         text: "Subheader {H3}",
    //         editable: true,
    //         get html(): string {
    //             return `<h3 class="text-center">${this.text}</h3>`;
    //         },
    //     },
    // },
    // {
    //     index: 2,
    //     label: "Text Box",
    //     help: "Text Box for workbook",
    //     buttonIcon: "paragraph",
    //     data: {
    //         editable: true,
    //         props: {
    //             text: "Text Box {p}",
    //         },
    //         get html(): string {
    //             return `<p class="text-center">${this.props.text}</p>`;
    //         },
    //     },
    // },
    // {
    //     index: 3,
    //     label: "Customer Name",
    //     help: "Name of the Customer this WB is for",
    //     buttonText: "CN",
    //     data: {
    //         editable: true,
    //         props: {
    //             text: "[ Customer Name ]",
    //         },
    //         get html(): string {
    //             return `<h3 class="text-center" data="customer.name">${this.props.text}</h3>`;
    //         },
    //     },
    // },
    // {
    //     index: 4,
    //     label: "Equipment Name",
    //     help: "WB Equipment Name",
    //     buttonText: "EQ",
    //     data: {
    //         editable: true,
    //         props: {
    //             text: 'equipmentType.value?.name',
    //         },
    //         get html(): string {
    //             return `<h5 class="text-center">${this.props.text}</h5>`;
    //         },
    //     },
    // },
]);
