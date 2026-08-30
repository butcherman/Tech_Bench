import { describe, expect, it, beforeEach } from "vitest";
import { mount } from "@vue/test-utils";
import { focus } from "@/core/directives/focusDirective";

beforeEach(() => {
    (document.activeElement as HTMLElement)?.blur();
});

describe("focus directive", () => {
    const mountWithDirective = (template: string) => {
        return mount(
            {
                template,
                directives: {
                    focus,
                },
            },
            {
                attachTo: document.body,
            },
        );
    };

    it("focuses the element itself when it is focusable", () => {
        const wrapper = mountWithDirective(`<input v-focus />`);

        expect(document.activeElement).toBe(wrapper.element);
    });

    it.each(["input", "textarea", "select", "button"])(
        "focuses a %s element",
        (tag) => {
            const wrapper = mountWithDirective(`<${tag} v-focus />`);

            expect(document.activeElement).toBe(wrapper.element);
        },
    );

    it("focuses an element with a valid tabindex", () => {
        const wrapper = mountWithDirective(`<div tabindex="0" v-focus />`);

        expect(document.activeElement).toBe(wrapper.element);
    });

    it("does not focus an element with tabindex -1", () => {
        const wrapper = mountWithDirective(`<div tabindex="-1" v-focus />`);

        expect(document.activeElement).not.toBe(wrapper.element);
    });

    it("focuses the first focusable descendant", () => {
        const wrapper = mountWithDirective(`
            <div v-focus>
                <span>Not focusable</span>
                <button>First</button>
                <button>Second</button>
            </div>
        `);

        const buttons = wrapper.findAll("button");

        expect(document.activeElement).toBe(buttons[0].element);
    });

    it("focuses the first focusable descendant when the root is not focusable", () => {
        const wrapper = mountWithDirective(`
            <div v-focus>
                <button>First</button>
            </div>
        `);

        expect(document.activeElement).toBe(wrapper.find("button").element);
    });

    it("does nothing when there are no focusable elements", () => {
        mountWithDirective(`
        <div v-focus>
            <span>Nothing focusable</span>
        </div>
    `);

        expect(document.activeElement).toBe(document.body);
    });
});
