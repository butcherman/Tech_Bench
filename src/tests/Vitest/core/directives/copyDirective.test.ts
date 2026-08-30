import { defineComponent, nextTick } from "vue";
import { mount } from "@vue/test-utils";
import { beforeEach, afterEach, describe, expect, it, vi } from "vitest";

import { copy } from "@/core/directives/copyDirective";

vi.mock("@/core/components/badges/CopyBadge.vue", () => ({
    default: defineComponent({
        props: {
            pointer: Boolean,
            variant: String,
        },
        emits: ["click"],
        template: `
            <button
                class="copy-badge"
                :data-variant="variant"
                @click="$emit('click')"
            >
                Copy
            </button>
        `,
    }),
}));

vi.mock("@/core/directives/tooltipDirective.js", () => ({
    tooltip: {},
}));

describe("copy directive", () => {
    let writeText: ReturnType<typeof vi.fn>;

    beforeEach(() => {
        vi.useFakeTimers();

        writeText = vi.fn().mockResolvedValue(undefined);

        Object.defineProperty(navigator, "clipboard", {
            configurable: true,
            value: {
                writeText,
            },
        });
    });

    afterEach(() => {
        vi.useRealTimers();
        vi.restoreAllMocks();
    });

    const TestComponent = defineComponent({
        directives: {
            copy,
        },

        props: {
            value: {
                type: String,
                required: true,
            },
        },

        template: `
        <div>
            <span v-copy="value" class="source">
                {{ value }}
            </span>
        </div>
    `,
    });

    const mountComponent = (value = "Copy this") => {
        return mount(TestComponent, {
            props: {
                value,
            },
        });
    };

    it("creates a copy badge after the element", async () => {
        const wrapper = mountComponent();

        await nextTick();

        const source = wrapper.find(".source").element;
        const badge = source.nextElementSibling;

        expect(badge).not.toBeNull();
        expect(badge?.classList.contains("ms-1")).toBe(true);
        expect(badge?.querySelector(".copy-badge")).not.toBeNull();
    });

    it("copies the bound value when the badge is clicked", async () => {
        const wrapper = mountComponent("Hello world");

        await nextTick();

        await wrapper.find(".copy-badge").trigger("click");

        expect(writeText).toHaveBeenCalledOnce();
        expect(writeText).toHaveBeenCalledWith("Hello world");
    });

    it("shows success state after copying", async () => {
        const wrapper = mountComponent();

        await nextTick();

        await wrapper.find(".copy-badge").trigger("click");
        await nextTick();

        expect(wrapper.find(".copy-badge").attributes("data-variant")).toBe(
            "success",
        );
    });

    it("shows failure state when copying fails", async () => {
        writeText.mockRejectedValueOnce(new Error("Clipboard failed"));

        const wrapper = mountComponent();

        await nextTick();

        await wrapper.find(".copy-badge").trigger("click");
        await nextTick();

        expect(wrapper.find(".copy-badge").attributes("data-variant")).toBe(
            "danger",
        );
    });

    it("resets the variant after 5 seconds", async () => {
        const wrapper = mountComponent();

        await nextTick();

        await wrapper.find(".copy-badge").trigger("click");
        await nextTick();

        expect(wrapper.find(".copy-badge").attributes("data-variant")).toBe(
            "success",
        );

        vi.advanceTimersByTime(5000);
        await nextTick();

        expect(wrapper.find(".copy-badge").attributes("data-variant")).toBe(
            "warning",
        );
    });

    it("copies the updated value", async () => {
        const wrapper = mountComponent("Initial");

        await nextTick();

        await wrapper.setProps({
            value: "Updated",
        });

        await nextTick();

        await wrapper.find(".copy-badge").trigger("click");

        expect(writeText).toHaveBeenCalledWith("Updated");
    });

    it("removes the copy badge when the element is unmounted", async () => {
        const wrapper = mountComponent();

        await nextTick();

        expect(wrapper.find(".copy-badge").exists()).toBe(true);

        wrapper.unmount();

        expect(document.querySelector(".copy-badge")).toBeNull();
    });

    it("clears the timeout when unmounted", async () => {
        const clearTimeoutSpy = vi.spyOn(globalThis, "clearTimeout");

        const wrapper = mountComponent();

        await nextTick();

        await wrapper.find(".copy-badge").trigger("click");
        await nextTick();

        wrapper.unmount();

        expect(clearTimeoutSpy).toHaveBeenCalled();
    });
});
