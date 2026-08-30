import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import ExpandBadge from "@/core/components/badges/ExpandBadge.vue";
import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";
import { VNodeProps } from "vue";

describe("ExpandBadge", () => {
    const mountAddBadge = (options = {}) => {
        return mount(ExpandBadge, {
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

    it("renders the default expand badge", () => {
        const wrapper = mountAddBadge({
            props: {
                expanded: true,
            },
        });

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props("variant")).toBe("info");
    });

    it("uses the correct icon when expanded", () => {
        const wrapper = mountAddBadge({
            props: {
                expanded: true,
            },
        });

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe(
            "fa-up-right-and-down-left-from-center",
        );
    });

    it("uses the correct icon when closed", () => {
        const wrapper = mountAddBadge({
            props: {
                expanded: false,
            },
        });

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe(
            "fa-down-left-and-up-right-to-center",
        );
    });

    it("defaults to the info variant", () => {
        const wrapper = mountAddBadge({
            props: {
                expanded: false,
            },
        });

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props("variant")).toBe("info");
    });

    it("passes the specified variant to BaseBadge", () => {
        const wrapper = mountAddBadge({
            props: {
                variant: "success",
                expanded: false,
            },
        });

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props("variant")).toBe("success");
    });

    it.each([["circle", true]])("passes %s to BaseBadge", (prop, value) => {
        const wrapper = mountAddBadge({
            props: {
                [prop]: value,
                expanded: false,
            },
        });

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props(prop as keyof VNodeProps)).toBe(value);
    });
});
