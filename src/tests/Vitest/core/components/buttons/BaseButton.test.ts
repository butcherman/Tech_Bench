import BaseButton from "@/core/components/buttons/BaseButton.vue";
import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";
import { Link } from "@inertiajs/vue3";

describe("Button", () => {
    const mountButton = (options = {}) => {
        return mount(BaseButton, {
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
            const wrapper = mountButton();

            expect(wrapper.element.tagName).toBe("BUTTON");
        });

        it("applies the base classes", () => {
            const wrapper = mountButton();

            expect(wrapper.classes()).toContain("rounded-lg");
            expect(wrapper.classes()).toContain("inline-block");
            expect(wrapper.classes()).toContain("text-center");
        });

        it("sets the button type to button", () => {
            const wrapper = mountButton();

            expect(wrapper.attributes("type")).toBe("button");
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
                },
            });

            const icon = wrapper.find(".fa-icon-stub");

            expect(icon.exists()).toBe(true);
            expect(icon.attributes("data-icon")).toBe("check");
        });

        it("does not render an icon when one is not provided", () => {
            const wrapper = mountButton();

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
                },
            });

            const classList = expectedClass.split(" ");

            classList.forEach((cl) => {
                expect(wrapper.classes()).toContain(cl);
            });
        });

        it("defaults to md size", () => {
            const wrapper = mountButton();

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
            const wrapper = mountButton();

            expect(wrapper.classes()).toContain("bg-blue-500");
            expect(wrapper.classes()).toContain("text-white");
            expect(wrapper.classes()).toContain("focus:outline-blue-600");
        });
    });

    describe("active", () => {
        it.each([
            ["primary", "bg-blue-700"],
            ["secondary", "bg-blue-500"],
            ["success", "bg-green-700"],
            ["danger", "bg-rose-800"],
            ["warning", "bg-yellow-600"],
            ["info", "bg-blue-600"],
            ["help", "bg-violet-800"],
            ["dark", "bg-gray-900"],
            ["light", "bg-neutral-500"],
            ["error", "bg-red-700"],
            ["none", "bg-blue-100"],
        ])(
            "applies the active class for variant %s",
            (variant, expectedClass) => {
                const wrapper = mountButton({
                    props: {
                        variant,
                        active: true,
                    },
                });

                expect(wrapper.classes()).toContain(expectedClass);
            },
        );

        it("does not apply an active class when inactive", () => {
            const wrapper = mountButton({
                props: {
                    active: false,
                    variant: "primary",
                },
            });

            expect(wrapper.classes()).not.toContain("bg-blue-700");
        });

        it("defaults to the primary active variant", () => {
            const wrapper = mountButton({
                props: {
                    active: true,
                },
            });

            expect(wrapper.classes()).toContain("bg-blue-700");
        });
    });

    describe("pill", () => {
        it("applies pill styling", () => {
            const wrapper = mountButton({
                props: {
                    pill: true,
                },
            });

            expect(wrapper.classes()).toContain("rounded-full!");
        });

        it("does not apply pill styling by default", () => {
            const wrapper = mountButton();

            expect(wrapper.classes()).not.toContain("rounded-full!");
        });
    });

    describe("flat", () => {
        it("applies shadow when flat is false", () => {
            const wrapper = mountButton({
                props: {
                    flat: false,
                },
            });

            expect(wrapper.classes()).toContain("shadow-xl");
        });

        it("applies shadow by default", () => {
            const wrapper = mountButton();

            expect(wrapper.classes()).toContain("shadow-xl");
        });

        it("does not apply shadow when flat is true", () => {
            const wrapper = mountButton({
                props: {
                    flat: true,
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
                },
            });

            expect(wrapper.classes()).toContain("pointer");
        });

        it("does not apply pointer class when disabled", () => {
            const wrapper = mountButton({
                props: {
                    disabled: true,
                },
            });

            expect(wrapper.classes()).not.toContain("pointer");
        });

        it("does not apply pointer class by default", () => {
            const wrapper = mountButton();

            expect(wrapper.classes()).toContain("pointer");
        });
    });

    describe("href", () => {
        it("renders a button when href is not provided", () => {
            const wrapper = mountButton();

            expect(wrapper.element.tagName).toBe("BUTTON");
        });

        it("renders an Inertia Link when href is provided", () => {
            const wrapper = mountButton({
                props: {
                    href: "/users",
                },
            });

            expect(wrapper.findComponent(Link).exists()).toBe(true);
        });

        it("passes href to the link", () => {
            const wrapper = mountButton({
                props: {
                    href: "/users",
                },
            });

            const link = wrapper.findComponent(Link);

            expect(link.props("href")).toBe("/users");
        });
    });

    describe("async", () => {
        it("passes async to the component", () => {
            const wrapper = mountButton({
                props: {
                    href: "/users",
                    async: true,
                },
            });

            const link = wrapper.findComponent(Link);

            expect(link.props("async")).toBe(true);
        });
    });
});
