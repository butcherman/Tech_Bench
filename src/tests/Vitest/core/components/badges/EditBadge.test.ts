import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import EditBadge from "@/core/components/badges/EditBadge.vue";
import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";
import { VNodeProps } from "vue";

describe("EditBadge", () => {
    const mountAddBadge = (options = {}) => {
        return mount(EditBadge, {
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

    it("renders the default edit badge", () => {
        const wrapper = mountAddBadge();

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props("variant")).toBe("warning");
    });

    it("uses the pencil icon by default", () => {
        const wrapper = mountAddBadge();

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("pencil");
    });

    it("defaults to the warning variant", () => {
        const wrapper = mountAddBadge();

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props("variant")).toBe("warning");
    });

    it("passes the specified variant to BaseBadge", () => {
        const wrapper = mountAddBadge({
            props: {
                variant: "success",
            },
        });

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props("variant")).toBe("success");
    });

    it("defaults to the pencil icon", () => {
        const wrapper = mountAddBadge();

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("pencil");
    });

    it("uses the specified icon", () => {
        const wrapper = mountAddBadge({
            props: {
                icon: "check",
            },
        });

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("check");
    });

    it("uses the specified text", () => {
        const wrapper = mountAddBadge({
            props: {
                icon: "",
                text: "Create User",
            },
        });

        expect(wrapper.text()).toContain("Create User");
        expect(wrapper.text()).not.toContain("Add");
    });

    it("passes slot content to BaseBadge", () => {
        const wrapper = mountAddBadge({
            slots: {
                default: "Custom Content",
            },
        });

        expect(wrapper.text()).toContain("Custom Content");
        expect(wrapper.text()).not.toContain("Add");
    });

    it("does not render the default icon when slot content is provided", () => {
        const wrapper = mountAddBadge({
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
        const wrapper = mountAddBadge({
            props: {
                [prop]: value,
            },
        });

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props(prop as keyof VNodeProps)).toBe(value);
    });
});
