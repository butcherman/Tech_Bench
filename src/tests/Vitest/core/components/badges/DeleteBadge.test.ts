import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import DeleteBadge from "@/core/components/badges/DeleteBadge.vue";
import { beforeEach, describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";
import { VNodeProps, nextTick } from "vue";

let outsideClickHandler: (() => void) | undefined;

beforeEach(() => {
    outsideClickHandler = undefined;
});

describe("DeleteBadge", () => {
    const mountDeleteBadge = (options = {}) => {
        return mount(DeleteBadge, {
            global: {
                directives: {
                    "on-click-outside": {
                        mounted(el, binding) {
                            outsideClickHandler = binding.value;
                        },
                    },
                },
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

    it("renders the default delete badge", () => {
        const wrapper = mountDeleteBadge();

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props("variant")).toBe("danger");
    });

    it("uses the trash-alt icon by default", () => {
        const wrapper = mountDeleteBadge();

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("trash-alt");
    });

    it("defaults to the danger variant", () => {
        const wrapper = mountDeleteBadge();

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props("variant")).toBe("danger");
    });

    it("passes the specified variant to BaseBadge", () => {
        const wrapper = mountDeleteBadge({
            props: {
                variant: "success",
            },
        });

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props("variant")).toBe("success");
    });

    it("defaults to the trash-alt icon", () => {
        const wrapper = mountDeleteBadge();

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("trash-alt");
    });

    it("uses the specified icon", () => {
        const wrapper = mountDeleteBadge({
            props: {
                icon: "check",
            },
        });

        const icon = wrapper.find(".fa-icon-stub");

        expect(icon.attributes("data-icon")).toBe("check");
    });

    it("uses the specified text", () => {
        const wrapper = mountDeleteBadge({
            props: {
                icon: "",
                text: "Delete",
            },
        });

        expect(wrapper.text()).toContain("Delete");
    });

    it("passes slot content to BaseBadge", () => {
        const wrapper = mountDeleteBadge({
            slots: {
                default: "Custom Content",
            },
        });

        expect(wrapper.text()).toContain("Custom Content");
        expect(wrapper.text()).not.toContain("Add");
    });

    it("does not render the default icon when slot content is provided", () => {
        const wrapper = mountDeleteBadge({
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
        const wrapper = mountDeleteBadge({
            props: {
                [prop]: value,
            },
        });

        const button = wrapper.findComponent(BaseBadge);

        expect(button.props(prop as keyof VNodeProps)).toBe(value);
    });

    describe("Confirmation", () => {
        it("does not show confirmation by default", () => {
            const wrapper = mountDeleteBadge({ props: { confirm: true } });

            expect(wrapper.find(".confirm-dialog").exists()).toBe(false);
        });

        it("shows confirmation when clicked and enabled", async () => {
            const wrapper = mountDeleteBadge({ props: { confirm: true } });

            await wrapper.findComponent(BaseBadge).trigger("click");

            expect(wrapper.find(".confirm-dialog").exists()).toBe(true);
        });

        it("does not show confirmation when confirm is disabled", async () => {
            const wrapper = mountDeleteBadge();

            await wrapper.findComponent(BaseBadge).trigger("click");

            expect(wrapper.find(".confirm-dialog").exists()).toBe(false);
        });

        it("emits yesClicked when Yes is clicked", async () => {
            const wrapper = mountDeleteBadge({ props: { confirm: true } });

            await wrapper.findComponent(BaseBadge).trigger("click");

            const buttons = wrapper.findAllComponents(BaseButton);

            await buttons[0].trigger("click");

            expect(wrapper.emitted("yesClicked")).toHaveLength(1);
            expect(wrapper.emitted("noClicked")).toBeUndefined();
            expect(wrapper.find(".confirm-dialog").exists()).toBe(false);
        });

        it("emits noClicked when No is clicked", async () => {
            const wrapper = mountDeleteBadge({ props: { confirm: true } });

            await wrapper.findComponent(BaseBadge).trigger("click");

            const buttons = wrapper.findAllComponents(BaseButton);

            await buttons[1].trigger("click");

            expect(wrapper.emitted("noClicked")).toHaveLength(1);
            expect(wrapper.emitted("yesClicked")).toBeUndefined();
            expect(wrapper.find(".confirm-dialog").exists()).toBe(false);
        });

        it("closes confirmation when clicking outside", async () => {
            const wrapper = mountDeleteBadge({
                props: {
                    confirm: true,
                },
            });

            await wrapper.findComponent(BaseBadge).trigger("click");

            expect(wrapper.find(".confirm-dialog").exists()).toBe(true);
            expect(outsideClickHandler).toBeDefined();

            outsideClickHandler?.();
            await nextTick();

            expect(wrapper.find(".confirm-dialog").exists()).toBe(false);
        });

        it("adds pointer to BaseBadge when confirmation is enabled", () => {
            const wrapper = mountDeleteBadge({ props: { confirm: true } });

            expect(wrapper.findComponent(BaseBadge).props("pointer")).toBe(
                true,
            );
        });
    });

    it.each([
        [undefined, undefined, undefined],
        [true, undefined, true],
        [undefined, true, true],
        [true, true, true],
    ])(
        "sets pointer to %s when pointer=%s and confirm=%s",
        (pointer, confirm, expected) => {
            const wrapper = mountDeleteBadge({
                props: {
                    pointer,
                    confirm,
                },
            });

            expect(wrapper.findComponent(BaseBadge).props("pointer")).toBe(
                expected,
            );
        },
    );
});
