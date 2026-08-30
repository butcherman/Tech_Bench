import Drawer from "@/core/components/Drawer.vue";
import { mount } from "@vue/test-utils";
import { describe, expect, it, vi } from "vitest";
import { afterEach } from "vitest";

afterEach(() => {
    vi.useRealTimers();
});

const outsideClickHandler = vi.fn();

const mountDrawer = (props = {}, options = {}) => {
    return mount(Drawer, {
        props: {
            modelValue: true,
            ...props,
        },
        attachTo: document.body,
        global: {
            directives: {
                "on-click-outside": {
                    mounted(el, binding) {
                        outsideClickHandler.mockImplementation(binding.value);
                    },
                },
            },
            stubs: {
                "fa-icon": {
                    template: "<span />",
                },
                Transition: {
                    template: "<div><slot /></div>",
                },
            },
        },
        ...options,
    });
};

describe("Drawer", () => {
    it("renders when show is true", () => {
        const wrapper = mountDrawer();

        const drawer = document.body.querySelector(".tb-drawer");

        expect(drawer).not.toBeNull();

        wrapper.unmount();
    });

    it("does not render when show is false", () => {
        const wrapper = mountDrawer({
            modelValue: false,
        });

        expect(document.body.querySelector(".tb-drawer")).toBeNull();

        wrapper.unmount();
    });

    it("emits update:modelValue when the close button is clicked", async () => {
        const wrapper = mountDrawer();

        const button = document.body.querySelector(".hide-button");

        expect(button).not.toBeNull();

        await button!.dispatchEvent(new MouseEvent("click"));

        expect(wrapper.emitted("update:modelValue")).toEqual([[false]]);

        wrapper.unmount();
    });

    it("closes when backdrop is clicked", async () => {
        const wrapper = mountDrawer();

        const backdrop = document.body.querySelector(".drawer-backdrop");

        await backdrop?.dispatchEvent(new MouseEvent("click"));

        expect(wrapper.emitted("update:modelValue")).toEqual([[false]]);

        wrapper.unmount();
    });

    it("renders the title", () => {
        const wrapper = mountDrawer({
            title: "My Drawer",
        });

        const header = document.body.querySelector("h5");

        expect(header?.textContent).toBe("My Drawer");

        wrapper.unmount();
    });

    it("renders the default slot", () => {
        const wrapper = mountDrawer(
            {},
            {
                slots: {
                    default: "<div data-testid='content'>Hello World</div>",
                },
            },
        );

        const slot = document.body.querySelector("[data-testid='content']");

        expect(slot?.textContent).toBe("Hello World");

        wrapper.unmount();
    });

    it.each([
        ["top", "top-0 left-0 right-0 border-b w-full min-h-96"],
        ["left", "top-0 left-0 border-e h-screen min-w-96"],
        ["right", "top-0 right-0 border-s h-screen min-w-96"],
    ])("uses the correct %s position", (position, expectedClass) => {
        const wrapper = mountDrawer({
            position,
        });

        const drawer = document.body.querySelector(".tb-drawer");
        const classList = expectedClass.split(" ");

        classList.forEach((cl) => {
            expect(drawer?.classList).toContain(cl);
        });

        wrapper.unmount();
    });

    it("defaults to bottom position", () => {
        const wrapper = mountDrawer();

        const drawer = document.body.querySelector(".tb-drawer");
        const expectedList = "bottom-0 left-0 right-0 border-t w-full min-h-96";
        const classList = expectedList.split(" ");

        classList.forEach((cl) => {
            expect(drawer?.classList).toContain(cl);
        });

        wrapper.unmount();
    });

    // it("closes when clicking outside", async () => {
    //     const wrapper = mountDrawer();

    //     outsideClickHandler();

    //     expect(wrapper.emitted("update:modelValue")).toEqual([[false]]);

    //     wrapper.unmount();
    // });
});
