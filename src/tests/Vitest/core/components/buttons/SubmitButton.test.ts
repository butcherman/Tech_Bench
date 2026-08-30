import SubmitButton from "@/core/components/buttons/SubmitButton.vue";
import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";

describe("Button", () => {
    const mountButton = (options = {}) => {
        return mount(SubmitButton, {
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

    describe("rendering", () => {
        it("renders a button by default", () => {
            const wrapper = mountButton({
                props: {
                    text: "Submit",
                },
            });

            expect(wrapper.element.tagName).toBe("BUTTON");
        });

        it("applies the base classes", () => {
            const wrapper = mountButton({
                props: {
                    text: "Submit",
                },
            });

            expect(wrapper.classes()).toContain("rounded-lg");
            expect(wrapper.classes()).toContain("inline-block");
            expect(wrapper.classes()).toContain("text-center");
        });

        it("sets the button type to button", () => {
            const wrapper = mountButton({
                props: {
                    text: "Submit",
                },
            });

            expect(wrapper.attributes("type")).toBe("submit");
        });
    });

    describe("text", () => {
        it("renders the text prop", () => {
            const wrapper = mountButton({
                props: {
                    text: "Save",
                },
            });

            expect(wrapper.text()).toContain("Save");
        });

        it("renders slot content instead of text", () => {
            const wrapper = mountButton({
                props: {
                    text: "Save",
                },
                slots: {
                    default: "Submit Form",
                },
            });

            expect(wrapper.text()).toBe("Submit Form");
            expect(wrapper.text()).not.toContain("Save");
        });
    });

    describe("icon", () => {
        it("renders the icon when provided", () => {
            const wrapper = mountButton({
                props: {
                    icon: "check",
                    text: "Submit",
                },
            });

            const icon = wrapper.find(".fa-icon-stub");

            expect(icon.exists()).toBe(true);
            expect(icon.attributes("data-icon")).toBe("check");
        });

        it("does not render an icon when one is not provided", () => {
            const wrapper = mountButton({
                props: {
                    text: "Submit",
                },
            });

            expect(wrapper.findComponent({ name: "fa-icon" }).exists()).toBe(
                false,
            );
        });

        it("renders icon fallback content when no slot is provided", () => {
            const wrapper = mountButton({
                props: {
                    icon: "save",
                    text: "Save",
                },
            });

            expect(wrapper.text()).toContain("Save");
        });

        it("does not render the icon when slot content is provided", () => {
            const wrapper = mountButton({
                props: {
                    icon: "save",
                    text: "Save",
                },
                slots: {
                    default: "Custom Content",
                },
            });

            expect(wrapper.findComponent({ name: "fa-icon" }).exists()).toBe(
                false,
            );
            expect(wrapper.text()).toBe("Custom Content");
        });
    });

    describe("size", () => {
        it.each([
            ["sm", "px-2 py-1"],
            ["md", "px-3 py-4"],
            ["lg", "px-3 py-6"],
        ])("applies the correct classes for size %s", (size, expectedClass) => {
            const wrapper = mountButton({
                props: {
                    size,
                    text: "Submit",
                },
            });

            const classList = expectedClass.split(" ");

            classList.forEach((cl) => {
                expect(wrapper.classes()).toContain(cl);
            });
        });

        it("defaults to md size", () => {
            const wrapper = mountButton({
                props: {
                    text: "Submit",
                },
            });

            expect(wrapper.classes()).toContain("px-3");
            expect(wrapper.classes()).toContain("py-4");
        });
    });

    describe("variant", () => {
        it.each([
            ["primary", "bg-blue-500 text-white focus:outline-blue-600"],
            ["secondary", "bg-blue-300 focus:outline-blue-400"],
            ["success", "bg-green-500 focus:outline-green-600"],
            ["danger", "bg-rose-600 text-white focus:outline-rose-700"],
            ["warning", "bg-yellow-400 focus:outline-yellow-500"],
            ["info", "bg-blue-400 text-white focus:outline-blue-500"],
            ["help", "bg-violet-600 text-white focus:outline-violet-700"],
            ["dark", "bg-gray-900 text-white focus:outline-gray-900"],
            ["light", "bg-neutral-300 focus:outline-neutral-400"],
            ["error", "bg-red-500 text-white focus:outline-red-800"],
            ["none", ""],
        ])(
            "applies the correct classes for variant %s",
            (variant, expectedClass) => {
                const wrapper = mountButton({
                    props: {
                        variant,
                        text: "Submit",
                    },
                });

                if (expectedClass) {
                    expectedClass.split(" ").forEach((className) => {
                        expect(wrapper.classes()).toContain(className);
                    });
                }
            },
        );

        it("defaults to the primary variant", () => {
            const wrapper = mountButton({
                props: {
                    text: "Submit",
                },
            });

            expect(wrapper.classes()).toContain("bg-blue-500");
            expect(wrapper.classes()).toContain("text-white");
            expect(wrapper.classes()).toContain("focus:outline-blue-600");
        });
    });

    describe("pill", () => {
        it("applies pill styling", () => {
            const wrapper = mountButton({
                props: {
                    pill: true,
                    text: "Submit",
                },
            });

            expect(wrapper.classes()).toContain("rounded-full!");
        });

        it("does not apply pill styling by default", () => {
            const wrapper = mountButton({
                props: {
                    text: "Submit",
                },
            });

            expect(wrapper.classes()).not.toContain("rounded-full!");
        });
    });

    describe("flat", () => {
        it("applies shadow when flat is false", () => {
            const wrapper = mountButton({
                props: {
                    flat: false,
                    text: "Submit",
                },
            });

            expect(wrapper.classes()).toContain("shadow-xl");
        });

        it("applies shadow by default", () => {
            const wrapper = mountButton({
                props: {
                    text: "Submit",
                },
            });

            expect(wrapper.classes()).toContain("shadow-xl");
        });

        it("does not apply shadow when flat is true", () => {
            const wrapper = mountButton({
                props: {
                    flat: true,
                    text: "Submit",
                },
            });

            expect(wrapper.classes()).not.toContain("shadow-xl");
        });
    });

    describe("disabled", () => {
        it("applies pointer class when enabled", () => {
            const wrapper = mountButton({
                props: {
                    disabled: false,
                    text: "Submit",
                },
            });

            expect(wrapper.classes()).toContain("pointer");
        });
    });
});
