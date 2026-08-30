import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import BooleanBadge from "@/core/components/badges/BooleanBadge.vue";
import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";
import { VNodeProps } from "vue";

describe("BooleanBadge", () => {
    const mountBooleanBadge = (options = {}) => {
        return mount(BooleanBadge, {
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

    it("renders the default true boolean badge", () => {
        const wrapper = mountBooleanBadge({
            props: {
                value: true,
            },
        });

        const button = wrapper.findComponent(BaseBadge);
        const icon = wrapper.find(".fa-icon-stub");

        expect(button.props("variant")).toBe("success");
        expect(icon.attributes("data-icon")).toBe("check");
    });

    it("renders the default false boolean badge", () => {
        const wrapper = mountBooleanBadge({
            props: {
                value: false,
            },
        });

        const button = wrapper.findComponent(BaseBadge);
        const icon = wrapper.find(".fa-icon-stub");

        expect(button.props("variant")).toBe("danger");
        expect(icon.attributes("data-icon")).toBe("xmark");
    });

    it("passes slot content to BaseBadge", () => {
        const wrapper = mountBooleanBadge({
            props: {
                value: true,
            },
            slots: {
                default: "Custom Content",
            },
        });

        expect(wrapper.text()).toContain("Custom Content");
        expect(wrapper.text()).not.toContain("Add");
    });

    it("does not render the default icon when slot content is provided", () => {
        const wrapper = mountBooleanBadge({
            props: {
                value: true,
            },
            slots: {
                default: "Custom Content",
            },
        });

        expect(wrapper.find(".fa-icon-stub").exists()).toBe(false);
    });

    it.each([["circle", true]])("passes %s to BaseBadge", (prop, value) => {
        const wrapper = mountBooleanBadge({
            props: {
                [prop]: value,
                value: true,
            },
        });

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props(prop as keyof VNodeProps)).toBe(value);
    });
});
