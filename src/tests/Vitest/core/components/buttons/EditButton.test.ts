import EditButton from "@/core/components/buttons/EditButton.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";
import { VNodeProps } from "vue";

describe("EditButton", () => {
    const mountEditButton = (options = {}) => {
        return mount(EditButton, {
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

    it("renders the default edit button", () => {
        const wrapper = mountEditButton();

        expect(wrapper.text()).toContain("Edit");

        const button = wrapper.findComponent(BaseButton);

        expect(button.props("variant")).toBe("warning");
    });

    it("uses the pencil icon by default", () => {
        const wrapper = mountEditButton();

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("pencil");
    });

    it("defaults to the warning variant", () => {
        const wrapper = mountEditButton();

        const button = wrapper.findComponent(BaseButton);

        expect(button.props("variant")).toBe("warning");
    });

    it("passes the specified variant to BaseButton", () => {
        const wrapper = mountEditButton({
            props: {
                variant: "success",
            },
        });

        const button = wrapper.findComponent(BaseButton);

        expect(button.props("variant")).toBe("success");
    });

    it("defaults to the pencil icon", () => {
        const wrapper = mountEditButton();

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("pencil");
    });

    it("uses the specified icon", () => {
        const wrapper = mountEditButton({
            props: {
                icon: "check",
            },
        });

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("check");
    });

    it("defaults to Edit text", () => {
        const wrapper = mountEditButton();

        expect(wrapper.text()).toContain("Edit");
    });

    it("uses the specified text", () => {
        const wrapper = mountEditButton({
            props: {
                text: "Create User",
            },
        });

        expect(wrapper.text()).toContain("Create User");
        expect(wrapper.text()).not.toContain("Edit");
    });

    it("passes slot content to BaseButton", () => {
        const wrapper = mountEditButton({
            slots: {
                default: "Custom Content",
            },
        });

        expect(wrapper.text()).toContain("Custom Content");
        expect(wrapper.text()).not.toContain("Edit");
    });

    it("does not render the default icon when slot content is provided", () => {
        const wrapper = mountEditButton({
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
        const wrapper = mountEditButton({
            props: {
                [prop]: value,
            },
        });

        const button = wrapper.findComponent(BaseButton);

        expect(button.props(prop as keyof VNodeProps)).toBe(value);
    });
});
