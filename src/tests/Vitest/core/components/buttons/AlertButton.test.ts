import AlertButton from "@/core/components/buttons/AlertButton.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";
import { VNodeProps } from "vue";

describe("AlertButton", () => {
    const mountAlertButton = (options = {}) => {
        return mount(AlertButton, {
            props: {
                text: "Alert",
            },
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

    it("renders the default warning button", () => {
        const wrapper = mountAlertButton();

        expect(wrapper.text()).toContain("Alert");

        const button = wrapper.findComponent(BaseButton);

        expect(button.props("variant")).toBe("warning");
    });

    it("uses the triangle-exclamation icon by default", () => {
        const wrapper = mountAlertButton();

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("triangle-exclamation");
    });

    it("defaults to the warning variant", () => {
        const wrapper = mountAlertButton();

        const button = wrapper.findComponent(BaseButton);

        expect(button.props("variant")).toBe("warning");
    });

    it("passes the specified variant to BaseButton", () => {
        const wrapper = mountAlertButton({
            props: {
                variant: "success",
            },
        });

        const button = wrapper.findComponent(BaseButton);

        expect(button.props("variant")).toBe("success");
    });

    it("uses the specified icon", () => {
        const wrapper = mountAlertButton({
            props: {
                icon: "check",
            },
        });

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("check");
    });

    it("uses the specified text", () => {
        const wrapper = mountAlertButton({
            props: {
                text: "Create User",
            },
        });

        expect(wrapper.text()).toContain("Create User");
        expect(wrapper.text()).not.toContain("Alert");
    });

    it("passes slot content to BaseButton", () => {
        const wrapper = mountAlertButton({
            slots: {
                default: "Custom Content",
            },
        });

        expect(wrapper.text()).toContain("Custom Content");
        expect(wrapper.text()).not.toContain("Alert");
    });

    it("does not render the default icon when slot content is provided", () => {
        const wrapper = mountAlertButton({
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
        const wrapper = mountAlertButton({
            props: {
                [prop]: value,
            },
        });

        const button = wrapper.findComponent(BaseButton);

        expect(button.props(prop as keyof VNodeProps)).toBe(value);
    });
});
