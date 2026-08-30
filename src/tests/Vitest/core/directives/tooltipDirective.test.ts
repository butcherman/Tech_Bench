import { beforeEach, describe, expect, it, vi } from "vitest";
import { mount } from "@vue/test-utils";
import { defineComponent, nextTick } from "vue";
import { tooltip } from "@/core/directives/tooltipDirective";

type ObjectDirective = {
    mounted: (el: HTMLElement, binding: unknown) => void;
    updated: (el: HTMLElement, arg: any) => void;
};

const directive = tooltip as ObjectDirective;

describe("tooltip directive", () => {
    beforeEach(() => {
        document.body.innerHTML = "";
        vi.useFakeTimers();
    });

    const mountTooltip = (
        binding: string | Record<string, unknown>,
        modifiers: Record<string, boolean> = {},
    ) => {
        const TestComponent = defineComponent({
            template: `<button v-tooltip="value">Test</button>`,
            data: () => ({
                value: binding,
            }),
        });

        return mount(TestComponent, {
            global: {
                directives: {
                    tooltip: {
                        ...tooltip,
                        mounted(el, binding) {
                            // Apply the modifiers supplied by the test.
                            binding.modifiers = modifiers;

                            directive.mounted?.(el, binding);
                        },
                    },
                },
            },
        });
    };

    it("creates a tooltip with the binding text", () => {
        const wrapper = mountTooltip("Hello world");

        const tip = document.body.querySelector(".tb-tooltip");

        expect(tip).not.toBeNull();
        expect(tip?.textContent).toBe("Hello world");
        expect(tip?.className).toBe("tb-tooltip tb-tooltip-top");

        wrapper.unmount();
    });

    it("defaults to top placement", () => {
        const wrapper = mountTooltip("Hello");

        const tip = document.body.querySelector(".tb-tooltip");

        expect(tip?.className).toBe("tb-tooltip tb-tooltip-top");

        wrapper.unmount();
    });

    it.each(["top", "bottom", "left", "right"] as const)(
        "uses the %s modifier for placement",
        (placement) => {
            const wrapper = mountTooltip("Hello", {
                [placement]: true,
            });

            const tip = document.body.querySelector(".tb-tooltip");

            expect(tip?.className).toBe(`tb-tooltip tb-tooltip-${placement}`);

            wrapper.unmount();
        },
    );

    it("supports object binding values", () => {
        const wrapper = mountTooltip({
            text: "Custom tooltip",
            placement: "bottom",
            delay: 500,
        });

        const tip = document.body.querySelector(".tb-tooltip");

        expect(tip?.textContent).toBe("Custom tooltip");
        expect(tip?.className).toBe("tb-tooltip tb-tooltip-bottom");

        wrapper.unmount();
    });

    it("defaults object placement to top", () => {
        const wrapper = mountTooltip({
            text: "Hello",
        });

        const tip = document.body.querySelector(".tb-tooltip");

        expect(tip?.className).toBe("tb-tooltip tb-tooltip-top");

        wrapper.unmount();
    });

    it("sets the initial tooltip styles", () => {
        const wrapper = mountTooltip("Hello");

        const tip = document.body.querySelector(".tb-tooltip") as HTMLElement;

        expect(tip.style.position).toBe("fixed");
        expect(tip.style.opacity).toBe("0");
        expect(tip.style.pointerEvents).toBe("none");

        wrapper.unmount();
    });

    it("shows the tooltip after the default delay on mouseenter", () => {
        const wrapper = mountTooltip("Hello");
        const button = wrapper.get("button");
        const tip = document.body.querySelector(".tb-tooltip") as HTMLElement;

        vi.spyOn(button.element, "getBoundingClientRect").mockReturnValue({
            top: 100,
            bottom: 120,
            left: 100,
            right: 200,
            width: 100,
            height: 20,
            x: 100,
            y: 100,
            toJSON: () => {},
        });

        vi.spyOn(tip, "getBoundingClientRect").mockReturnValue({
            top: 0,
            bottom: 0,
            left: 0,
            right: 0,
            width: 40,
            height: 20,
            x: 0,
            y: 0,
            toJSON: () => {},
        });

        button.element.dispatchEvent(new MouseEvent("mouseenter"));

        expect(tip.style.opacity).toBe("0");

        vi.advanceTimersByTime(249);
        expect(tip.style.opacity).toBe("0");

        vi.advanceTimersByTime(1);
        expect(tip.style.opacity).toBe("1");

        wrapper.unmount();
    });

    it("uses a custom delay", () => {
        const wrapper = mountTooltip({
            text: "Delayed",
            delay: 1000,
        });

        const button = wrapper.get("button");
        const tip = document.body.querySelector(".tb-tooltip") as HTMLElement;

        button.element.dispatchEvent(new MouseEvent("mouseenter"));

        vi.advanceTimersByTime(999);
        expect(tip.style.opacity).toBe("0");

        vi.advanceTimersByTime(1);
        expect(tip.style.opacity).toBe("1");

        wrapper.unmount();
    });

    it("shows the tooltip on focus", () => {
        const wrapper = mountTooltip("Focused");
        const button = wrapper.get("button");
        const tip = document.body.querySelector(".tb-tooltip") as HTMLElement;

        button.element.dispatchEvent(new FocusEvent("focus"));

        vi.advanceTimersByTime(250);

        expect(tip.style.opacity).toBe("1");

        wrapper.unmount();
    });

    it("hides the tooltip on mouseleave", () => {
        const wrapper = mountTooltip("Hello");
        const button = wrapper.get("button");
        const tip = document.body.querySelector(".tb-tooltip") as HTMLElement;

        button.element.dispatchEvent(new MouseEvent("mouseenter"));
        vi.advanceTimersByTime(250);

        expect(tip.style.opacity).toBe("1");

        button.element.dispatchEvent(new MouseEvent("mouseleave"));

        expect(tip.style.opacity).toBe("0");

        wrapper.unmount();
    });

    it("hides the tooltip on blur", () => {
        const wrapper = mountTooltip("Hello");
        const button = wrapper.get("button");
        const tip = document.body.querySelector(".tb-tooltip") as HTMLElement;

        button.element.dispatchEvent(new FocusEvent("focus"));
        vi.advanceTimersByTime(250);

        expect(tip.style.opacity).toBe("1");

        button.element.dispatchEvent(new FocusEvent("blur"));

        expect(tip.style.opacity).toBe("0");

        wrapper.unmount();
    });

    it("cancels a pending tooltip when the mouse leaves", () => {
        const wrapper = mountTooltip("Hello");
        const button = wrapper.get("button");
        const tip = document.body.querySelector(".tb-tooltip") as HTMLElement;

        button.element.dispatchEvent(new MouseEvent("mouseenter"));

        vi.advanceTimersByTime(100);

        button.element.dispatchEvent(new MouseEvent("mouseleave"));

        vi.advanceTimersByTime(200);

        expect(tip.style.opacity).toBe("0");

        wrapper.unmount();
    });

    it("cancels a pending tooltip when the element blurs", () => {
        const wrapper = mountTooltip("Hello");
        const button = wrapper.get("button");
        const tip = document.body.querySelector(".tb-tooltip") as HTMLElement;

        button.element.dispatchEvent(new FocusEvent("focus"));

        vi.advanceTimersByTime(100);

        button.element.dispatchEvent(new FocusEvent("blur"));

        vi.advanceTimersByTime(200);

        expect(tip.style.opacity).toBe("0");

        wrapper.unmount();
    });

    it("updates the tooltip text", async () => {
        const TestComponent = defineComponent({
            template: `<button v-tooltip="value">Test</button>`,
            data: () => ({
                value: "Original",
            }),
        });

        const wrapper = mount(TestComponent, {
            global: {
                directives: {
                    tooltip,
                },
            },
        });

        const tip = document.body.querySelector(".tb-tooltip") as HTMLElement;

        expect(tip.textContent).toBe("Original");

        await wrapper.setData({
            value: "Updated",
        });

        await nextTick();

        expect(tip.textContent).toBe("Updated");

        wrapper.unmount();
    });

    it("updates the tooltip placement", async () => {
        const TestComponent = defineComponent({
            template: `<button v-tooltip.bottom="value">Test</button>`,
            data: () => ({
                value: "Original",
            }),
        });

        const wrapper = mount(TestComponent, {
            global: {
                directives: {
                    tooltip,
                },
            },
        });

        const tip = document.body.querySelector(".tb-tooltip") as HTMLElement;

        expect(tip.className).toBe("tb-tooltip tb-tooltip-bottom");

        await wrapper.setData({
            value: {
                text: "Updated",
                placement: "right",
            },
        });

        await nextTick();

        expect(tip.textContent).toBe("Updated");
        expect(tip.className).toBe("tb-tooltip tb-tooltip-right");

        wrapper.unmount();
    });

    it("does nothing during updated if no tooltip exists", () => {
        const el = document.createElement("button");

        expect(() => {
            directive.updated?.(el, {
                value: "Hello",
                oldValue: undefined,
                arg: undefined,
                modifiers: {},
                instance: null,
                dir: tooltip,
            } as never);
        }).not.toThrow();
    });

    it("removes the tooltip when unmounted", () => {
        const wrapper = mountTooltip("Hello");

        expect(document.body.querySelector(".tb-tooltip")).not.toBeNull();

        wrapper.unmount();

        expect(document.body.querySelector(".tb-tooltip")).toBeNull();
    });

    it("removes event listeners when unmounted", () => {
        const wrapper = mountTooltip("Hello");
        const button = wrapper.get("button");

        const addSpy = vi.spyOn(button.element, "addEventListener");
        const removeSpy = vi.spyOn(button.element, "removeEventListener");

        // The listeners were already registered during mount, so remount
        // a fresh instance with spies in place.
        wrapper.unmount();

        expect(removeSpy).toHaveBeenCalledWith(
            "mouseenter",
            expect.any(Function),
        );
        expect(removeSpy).toHaveBeenCalledWith(
            "mouseleave",
            expect.any(Function),
        );
        expect(removeSpy).toHaveBeenCalledWith("focus", expect.any(Function));
        expect(removeSpy).toHaveBeenCalledWith("blur", expect.any(Function));

        addSpy.mockRestore();
    });

    it.each([
        {
            placement: "top",
            expectedTop: "72px",
            expectedLeft: "130px",
        },
        {
            placement: "bottom",
            expectedTop: "128px",
            expectedLeft: "130px",
        },
        {
            placement: "left",
            expectedTop: "100px",
            expectedLeft: "52px",
        },
        {
            placement: "right",
            expectedTop: "100px",
            expectedLeft: "208px",
        },
    ])(
        "positions the tooltip correctly for $placement placement",
        ({ placement, expectedTop, expectedLeft }) => {
            const wrapper = mountTooltip({
                text: "Hello",
                placement,
                delay: 0,
            });

            const button = wrapper.get("button");
            const tip = document.body.querySelector(
                ".tb-tooltip",
            ) as HTMLElement;

            vi.spyOn(button.element, "getBoundingClientRect").mockReturnValue({
                top: 100,
                bottom: 120,
                left: 100,
                right: 200,
                width: 100,
                height: 20,
                x: 100,
                y: 100,
                toJSON: () => {},
            });

            vi.spyOn(tip, "getBoundingClientRect").mockReturnValue({
                top: 0,
                bottom: 20,
                left: 0,
                right: 40,
                width: 40,
                height: 20,
                x: 0,
                y: 0,
                toJSON: () => {},
            });

            button.element.dispatchEvent(new MouseEvent("mouseenter"));
            vi.advanceTimersByTime(0);

            expect(tip.style.top).toBe(expectedTop);
            expect(tip.style.left).toBe(expectedLeft);

            wrapper.unmount();
        },
    );
});
