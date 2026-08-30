import AddButton from "@/core/components/buttons/AddButton.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";
import { VNodeProps } from "vue";

describe("AddButton", () => {
    const mountAddButton = (options = {}) => {
        return mount(AddButton, {
            global: {
                stubs: {
                    Link: true,
                    "fa-icon": {
                        props: ["icon"],
                        template: `<span class="fa-icon-stub" :data-icon="icon" />`,
                    },
                },
            },
            ...options,
        });
    };

    it("renders the default add button", () => {
        const wrapper = mountAddButton();

        expect(wrapper.text()).toContain("Add");

        const button = wrapper.findComponent(BaseButton);

        expect(button.props("variant")).toBe("info");
    });

    it("uses the plus icon by default", () => {
        const wrapper = mountAddButton();

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("plus");
    });

    it("defaults to the info variant", () => {
        const wrapper = mountAddButton();

        const button = wrapper.findComponent(BaseButton);

        expect(button.props("variant")).toBe("info");
    });

    it("passes the specified variant to BaseButton", () => {
        const wrapper = mountAddButton({
            props: {
                variant: "success",
            },
        });

        const button = wrapper.findComponent(BaseButton);

        expect(button.props("variant")).toBe("success");
    });

    it("defaults to the plus icon", () => {
        const wrapper = mountAddButton();

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("plus");
    });

    it("uses the specified icon", () => {
        const wrapper = mountAddButton({
            props: {
                icon: "check",
            },
        });

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("check");
    });

    it("defaults to Add text", () => {
        const wrapper = mountAddButton();

        expect(wrapper.text()).toContain("Add");
    });

    it("uses the specified text", () => {
        const wrapper = mountAddButton({
            props: {
                text: "Create User",
            },
        });

        expect(wrapper.text()).toContain("Create User");
        expect(wrapper.text()).not.toContain("Add");
    });

    it("passes slot content to BaseButton", () => {
        const wrapper = mountAddButton({
            slots: {
                default: "Custom Content",
            },
        });

        expect(wrapper.text()).toContain("Custom Content");
        expect(wrapper.text()).not.toContain("Add");
    });

    it("does not render the default icon when slot content is provided", () => {
        const wrapper = mountAddButton({
            slots: {
                default: "Custom Content",
            },
        });

        expect(wrapper.find(".fa-icon-stub").exists()).toBe(false);
    });

    it.each([
        ["async", true],
        ["flat", true],
        ["pill", true],
        ["href", "/users"],
        ["size", "lg"],
    ])("passes %s to BaseButton", (prop, value) => {
        const wrapper = mountAddButton({
            props: {
                [prop]: value,
            },
        });

        const button = wrapper.findComponent(BaseButton);

        expect(button.props(prop as keyof VNodeProps)).toBe(value);
    });
});
