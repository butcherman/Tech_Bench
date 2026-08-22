import Modal from "@/core/components/Modal.vue";
import { mount } from "@vue/test-utils";
import { describe, expect, it, vi } from "vitest";
import { afterEach } from "vitest";

afterEach(() => {
    vi.useRealTimers();
});

const outsideClickHandler = vi.fn();

const mountModal = (props = {}, options = {}) => {
    return mount(Modal, {
        props: {
            show: true,
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
                "fa-icon": true,
                Transition: {
                    template: "<div><slot /></div>",
                },
            },
        },
        ...options,
    });
};

describe("Modal", () => {
    it("renders when show is true", () => {
        const wrapper = mountModal();

        const modal = document.body.querySelector(".tb-modal");

        expect(modal).not.toBeNull();

        wrapper.unmount();
    });

    it("does not render when show is false", () => {
        const wrapper = mountModal({
            show: false,
        });

        expect(document.body.querySelector(".tb-modal")).toBeNull();

        wrapper.unmount();
    });

    it("emits update:show when the close button is clicked", async () => {
        const wrapper = mountModal();

        const button = document.body.querySelector(".hide-button");

        expect(button).not.toBeNull();

        await button!.dispatchEvent(new MouseEvent("click"));

        expect(wrapper.emitted("update:show")).toEqual([[false]]);

        wrapper.unmount();
    });

    it("does not render the close button when hideClose is true", () => {
        const wrapper = mountModal({
            hideClose: true,
        });

        const button = document.body.querySelector(".hide-button");

        expect(button).toBeNull();

        wrapper.unmount();
    });

    it("renders the title", () => {
        const wrapper = mountModal({
            title: "My Modal",
        });

        const header = document.body.querySelector("h5");

        expect(header?.textContent).toBe("My Modal");

        wrapper.unmount();
    });

    it("renders the header slot instead of the title", () => {
        const wrapper = mountModal(
            {},
            {
                slots: {
                    header: "<h2 class='custom-header'>Custom Header</h2>",
                },
            },
        );

        const header = document.body.querySelector(".custom-header");

        expect(header?.textContent).toBe("Custom Header");

        expect(document.querySelector("h5")).toBeNull();

        wrapper.unmount();
    });

    it("renders the default slot", () => {
        const wrapper = mountModal(
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

    it("renders the footer slot", () => {
        const wrapper = mountModal(
            {},
            {
                slots: {
                    footer: "<button data-testid='footer'>Save</button>",
                },
            },
        );

        const slot = document.body.querySelector("[data-testid='footer']");

        expect(slot?.textContent).toBe("Save");

        wrapper.unmount();
    });

    it.each([
        ["sm", "w-1/2"],
        ["md", "w-3/4"],
        ["lg", "w-full"],
    ])("uses the correct %s size", (size, expectedClass) => {
        const wrapper = mountModal({
            size,
        });

        const modal = document.body.querySelector(".tb-modal-body");

        expect(modal?.classList).toContain(expectedClass);

        wrapper.unmount();
    });

    it.each([
        ["top", "items-start"],
        ["center", "items-center"],
        ["bottom", "items-end"],
    ])("uses the correct %s position", (position, expectedClass) => {
        const wrapper = mountModal({
            position,
        });

        const backdrop = document.body.querySelector(".tb-modal");

        expect(backdrop?.classList).toContain(expectedClass);

        wrapper.unmount();
    });

    it("defaults to center position", () => {
        const wrapper = mountModal();

        const backdrop = document.body.querySelector(".tb-modal");

        expect(backdrop?.classList).toContain("items-center");

        wrapper.unmount();
    });

    it("defaults to medium size", () => {
        const wrapper = mountModal();

        const backdrop = document.body.querySelector(".tb-modal-body");

        expect(backdrop?.classList).toContain("w-3/4");

        wrapper.unmount();
    });

    it("shows the backdrop by default", () => {
        const wrapper = mountModal();

        const backdrop = document.body.querySelector(".tb-modal");

        expect(backdrop?.classList).toContain("bg-gray-500/75");

        wrapper.unmount();
    });

    it("hides the backdrop when hideBackdrop is true", () => {
        const wrapper = mountModal({
            hideBackdrop: true,
        });

        const backdrop = document.body.querySelector(".tb-modal");

        expect(backdrop?.classList).not.toContain("bg-gray-500/75");

        wrapper.unmount();
    });

    it("closes when clicking outside", async () => {
        const wrapper = mountModal();

        outsideClickHandler();

        expect(wrapper.emitted("update:show")).toEqual([[false]]);
    });

    it("prevents closing when preventOutsideClick is true", () => {
        const wrapper = mountModal({
            preventOutsideClick: true,
        });

        outsideClickHandler();

        expect(wrapper.emitted("hidePrevented")).toHaveLength(1);
        expect(wrapper.emitted("update:show")).toBeUndefined();
    });

    // it("shows attention when outside click is prevented", () => {
    //     vi.useFakeTimers();

    //     const wrapper = mountModal({
    //         preventOutsideClick: true,
    //     });

    //     const modal = document.body.querySelector(".tb-modal-body");

    //     outsideClickHandler();

    //     expect(modal?.classList).toContain("attention");

    //     vi.advanceTimersByTime(1000);

    //     expect(modal?.classList).not.toContain("attention");

    //     vi.useRealTimers();

    //     wrapper.unmount();
    // });

    it("emits show when show changes to true", async () => {
        const wrapper = mountModal({
            show: false,
        });

        await wrapper.setProps({
            show: true,
        });

        expect(wrapper.emitted("show")).toHaveLength(1);
    });

    it("emits hide when show changes to false", async () => {
        const wrapper = mountModal();

        await wrapper.setProps({
            show: false,
        });

        expect(wrapper.emitted("hide")).toHaveLength(1);
    });

    it("emits shown after the modal enters", async () => {
        const wrapper = mountModal(
            {},
            {
                global: {
                    stubs: {
                        Transition: {
                            template: "<div><slot /></div>",
                            emits: ["after-enter", "after-leave"],
                        },
                    },
                },
            },
        );

        await wrapper
            .findComponent({ name: "Transition" })
            .vm.$emit("after-enter");

        expect(wrapper.emitted("shown")).toHaveLength(1);
    });
});
