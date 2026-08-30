import { describe, expect, it, vi } from "vitest";
import { defineComponent, h, withDirectives } from "vue";
import { mount } from "@vue/test-utils";
import { tabTrap } from "@/core/directives/tabTrapDirective";

type ObjectDirective = {
    unmounted: (
        el: HTMLElement,
        binding: unknown,
        vnode: unknown,
        prevVnode: unknown,
    ) => void;
};

const directive = tabTrap as ObjectDirective;

describe("tabTrap directive", () => {
    const mountTabTrap = (html: ReturnType<typeof h>[]) => {
        const Component = defineComponent({
            setup() {
                return () =>
                    withDirectives(h("div", { "data-testid": "trap" }, html), [
                        [tabTrap],
                    ]);
            },
        });

        const wrapper = mount(Component, {
            attachTo: document.body,
        });
        const trap = wrapper.get("[data-testid='trap']");

        // JSDOM doesn't implement layout, so offsetParent is null
        // for everything. Make focusable elements appear visible.
        trap.element
            .querySelectorAll<HTMLElement>(
                "button, input, select, textarea, a, [tabindex]",
            )
            .forEach((element) => {
                Object.defineProperty(element, "offsetParent", {
                    configurable: true,
                    value: trap.element,
                });
            });

        return {
            wrapper,
            trap,
        };
    };

    it("moves focus from the last element to the first on Tab", () => {
        const { trap } = mountTabTrap([
            h("button", { id: "first" }, "First"),
            h("button", { id: "middle" }, "Middle"),
            h("button", { id: "last" }, "Last"),
        ]);

        const first = trap.get("#first").element as HTMLElement;
        const last = trap.get("#last").element as HTMLElement;

        last.focus();

        expect(document.activeElement).toBe(last);

        const event = new KeyboardEvent("keydown", {
            key: "Tab",
            bubbles: true,
            cancelable: true,
        });

        trap.element.dispatchEvent(event);

        expect(event.defaultPrevented).toBe(true);
        expect(document.activeElement).toBe(first);
    });

    it("moves focus from the first element to the last on Shift+Tab", () => {
        const { trap } = mountTabTrap([
            h("button", { id: "first" }, "First"),
            h("button", { id: "middle" }, "Middle"),
            h("button", { id: "last" }, "Last"),
        ]);

        const first = trap.get("#first").element as HTMLElement;
        const last = trap.get("#last").element as HTMLElement;

        first.focus();

        expect(document.activeElement).toBe(first);

        const event = new KeyboardEvent("keydown", {
            key: "Tab",
            shiftKey: true,
            bubbles: true,
            cancelable: true,
        });

        trap.element.dispatchEvent(event);

        expect(event.defaultPrevented).toBe(true);
        expect(document.activeElement).toBe(last);
    });

    it("does not trap Tab when focus is on a middle element", () => {
        const { trap } = mountTabTrap([
            h("button", { id: "first" }, "First"),
            h("button", { id: "middle" }, "Middle"),
            h("button", { id: "last" }, "Last"),
        ]);

        const middle = trap.get("#middle").element as HTMLElement;

        middle.focus();

        const event = new KeyboardEvent("keydown", {
            key: "Tab",
            shiftKey: false,
            bubbles: true,
            cancelable: true,
        });

        trap.element.dispatchEvent(event);

        expect(event.defaultPrevented).toBe(false);
        expect(document.activeElement).toBe(middle);
    });

    it("does not trap Shift+Tab when focus is on a middle element", () => {
        const { trap } = mountTabTrap([
            h("button", { id: "first" }, "First"),
            h("button", { id: "middle" }, "Middle"),
            h("button", { id: "last" }, "Last"),
        ]);

        const middle = trap.get("#middle").element as HTMLElement;

        middle.focus();

        const event = new KeyboardEvent("keydown", {
            key: "Tab",
            shiftKey: true,
            bubbles: true,
            cancelable: true,
        });

        trap.element.dispatchEvent(event);

        expect(event.defaultPrevented).toBe(false);
        expect(document.activeElement).toBe(middle);
    });

    it("ignores non-Tab keys", () => {
        const { trap } = mountTabTrap([
            h("button", { id: "first" }, "First"),
            h("button", { id: "last" }, "Last"),
        ]);

        const last = trap.get("#last").element as HTMLElement;

        last.focus();

        const event = new KeyboardEvent("keydown", {
            key: "Enter",
            shiftKey: false,
            bubbles: true,
            cancelable: true,
        });

        trap.element.dispatchEvent(event);

        expect(event.defaultPrevented).toBe(false);
        expect(document.activeElement).toBe(last);
    });

    it("does nothing when there are no visible focusable elements", () => {
        const { trap } = mountTabTrap([
            h("button", { disabled: true }),
            h("input", { type: "hidden" }),
            h("div", { tabindex: "-1" }),
        ]);

        const event = new KeyboardEvent("keydown", {
            key: "Tab",
            shiftKey: false,
            bubbles: true,
            cancelable: true,
        });

        trap.element.dispatchEvent(event);

        expect(event.defaultPrevented).toBe(false);
    });

    it("ignores disabled, hidden, and tabindex -1 elements", () => {
        const { trap } = mountTabTrap([
            h("button", {
                id: "disabled",
                disabled: true,
            }),
            h("input", {
                id: "hidden",
                type: "hidden",
            }),
            h("div", {
                id: "excluded",
                tabindex: "-1",
            }),
            h("button", {
                id: "first",
            }),
            h("button", {
                id: "last",
            }),
        ]);

        const first = trap.get("#first").element as HTMLElement;
        const last = trap.get("#last").element as HTMLElement;

        last.focus();

        const event = new KeyboardEvent("keydown", {
            key: "Tab",
            shiftKey: false,
            bubbles: true,
            cancelable: true,
        });

        trap.element.dispatchEvent(event);

        expect(event.defaultPrevented).toBe(true);
        expect(document.activeElement).toBe(first);
    });

    it("ignores invisible focusable elements", () => {
        const { trap } = mountTabTrap([
            h("button", { id: "invisible" }),
            h("button", { id: "first" }),
            h("button", { id: "last" }),
        ]);

        const invisible = trap.get("#invisible").element as HTMLElement;
        const first = trap.get("#first").element as HTMLElement;
        const last = trap.get("#last").element as HTMLElement;

        Object.defineProperty(invisible, "offsetParent", {
            configurable: true,
            value: null,
        });

        last.focus();

        const event = new KeyboardEvent("keydown", {
            key: "Tab",
            shiftKey: false,
            bubbles: true,
            cancelable: true,
        });

        trap.element.dispatchEvent(event);

        expect(document.activeElement).toBe(first);
    });

    it("removes the keydown listener when unmounted", () => {
        const { wrapper, trap } = mountTabTrap([
            h("button", { id: "first" }),
            h("button", { id: "last" }),
        ]);

        const removeEventListener = vi.spyOn(
            trap.element,
            "removeEventListener",
        );

        wrapper.unmount();

        expect(removeEventListener).toHaveBeenCalledWith(
            "keydown",
            expect.any(Function),
        );
    });

    it("does nothing when no handler is stored", () => {
        const el = document.createElement("div");

        const removeEventListener = vi.spyOn(el, "removeEventListener");

        directive.unmounted(el, {}, {}, {});

        expect(removeEventListener).not.toHaveBeenCalled();
    });
});
