import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";
import Card from "@/core/components/Card.vue";

describe("Card", () => {
    const mountCard = (options = {}) => {
        return mount(Card, options);
    };

    it("renders the default slot", () => {
        const wrapper = mountCard({
            slots: {
                default: "Card content",
            },
        });

        expect(wrapper.text()).toContain("Card content");
    });

    it("does not render the title when no title or title slot is provided", () => {
        const wrapper = mountCard();

        expect(wrapper.find(".card-title").exists()).toBe(false);
    });

    it("renders the title prop", () => {
        const wrapper = mountCard({
            props: {
                title: "My Card",
            },
        });

        const title = wrapper.find(".card-title");

        expect(title.exists()).toBe(true);
        expect(title.text()).toContain("My Card");
    });

    it("renders the title slot", () => {
        const wrapper = mountCard({
            props: {
                title: "Prop title",
            },
            slots: {
                title: "Slot title",
            },
        });

        expect(wrapper.find(".card-title").text()).toContain("Slot title");
    });

    it("uses the title slot instead of the title prop", () => {
        const wrapper = mountCard({
            props: {
                title: "Prop title",
            },
            slots: {
                title: "Slot title",
            },
        });

        const title = wrapper.find(".card-title");

        expect(title.text()).toContain("Slot title");
        expect(title.text()).not.toContain("Prop title");
    });

    it("renders the append-title slot", () => {
        const wrapper = mountCard({
            props: {
                title: "My Card",
            },
            slots: {
                "append-title": "<button>Action</button>",
            },
        });

        expect(wrapper.find(".card-title button").exists()).toBe(true);
        expect(wrapper.find(".card-title button").text()).toBe("Action");
    });

    it("renders the footer when the footer slot is provided", () => {
        const wrapper = mountCard({
            slots: {
                footer: "Card footer",
            },
        });

        const footer = wrapper.find(".border-t");

        expect(footer.exists()).toBe(true);
        expect(footer.text()).toContain("Card footer");
    });

    it("does not render the footer when the footer slot is not provided", () => {
        const wrapper = mountCard();

        expect(wrapper.find(".border-t").exists()).toBe(false);
    });

    it.each([
        ["sm", ["w-full", "md:w-1/3", "xl:w-1/4"]],
        ["md", ["w-full", "lg:w-1/2", "xl:w-1/3"]],
        ["lg", ["w-full"]],
    ])("applies the correct classes for size %s", (size, expectedClasses) => {
        const wrapper = mountCard({
            props: {
                size,
            },
        });

        expect(wrapper.classes()).toEqual(
            expect.arrayContaining(expectedClasses),
        );
    });

    it("defaults to the lg size", () => {
        const wrapper = mountCard();

        expect(wrapper.classes()).toContain("w-full");
    });
});
