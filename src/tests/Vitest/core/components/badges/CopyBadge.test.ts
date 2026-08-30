import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import CopyBadge from "@/core/components/badges/CopyBadge.vue";
import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";
import { VNodeProps } from "vue";

describe("CopyBadge", () => {
    const mountCopyBadge = (options = {}) => {
        return mount(CopyBadge, {
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

    it("renders the default add badge", () => {
        const wrapper = mountCopyBadge();

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props("variant")).toBe("warning");
    });

    it("uses the copy icon by default", () => {
        const wrapper = mountCopyBadge();

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("copy");
    });

    it("defaults to the warning variant", () => {
        const wrapper = mountCopyBadge();

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props("variant")).toBe("warning");
    });

    it("passes the specified variant to BaseBadge", () => {
        const wrapper = mountCopyBadge({
            props: {
                variant: "success",
            },
        });

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props("variant")).toBe("success");
    });

    it("uses the specified icon", () => {
        const wrapper = mountCopyBadge({
            props: {
                icon: "check",
            },
        });

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("check");
    });

    it("uses the specified text", () => {
        const wrapper = mountCopyBadge({
            props: {
                text: "Create User",
                icon: "",
            },
        });

        expect(wrapper.text()).toContain("Create User");
        expect(wrapper.text()).not.toContain("Add");
    });

    it("passes slot content to BaseBadge", () => {
        const wrapper = mountCopyBadge({
            slots: {
                default: "Custom Content",
            },
        });

        expect(wrapper.text()).toContain("Custom Content");
        expect(wrapper.text()).not.toContain("Add");
    });

    it("does not render the default icon when slot content is provided", () => {
        const wrapper = mountCopyBadge({
            slots: {
                default: "Custom Content",
            },
        });

        expect(wrapper.find(".fa-icon-stub").exists()).toBe(false);
    });

    it.each([
        ["circle", true],
        ["href", "/users"],
        ["size", "lg"],
    ])("passes %s to BaseBadge", (prop, value) => {
        const wrapper = mountCopyBadge({
            props: {
                [prop]: value,
            },
        });

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props(prop as keyof VNodeProps)).toBe(value);
    });
});
