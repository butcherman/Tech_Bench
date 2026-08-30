import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";
import Collapse from "@/core/components/Collapse.vue";

describe("Collapse", () => {
    const mountCollapse = (options = {}) => {
        return mount(Collapse, {
            global: {
                stubs: {
                    Transition: false,
                },
            },
            ...options,
        });
    };

    it("renders the default slot", () => {
        const wrapper = mountCollapse({
            props: {
                show: true,
            },
            slots: {
                default: "Collapse Content",
            },
        });

        expect(wrapper.text()).toContain("Collapse Content");
    });

    it("is hidden when collapsed", () => {
        const wrapper = mountCollapse({
            props: {
                show: false,
            },
            slots: {
                default: "Collapse Content",
            },
        });

        const content = wrapper.find("div");

        expect(content.element.style.display).toBe("none");
    });
});
