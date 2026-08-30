import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";
import { Link } from "@inertiajs/vue3";

describe("Badge", () => {
    const mountBadge = (options = {}) => {
        return mount(BaseBadge, {
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
            const wrapper = mountBadge();

            expect(wrapper.element.tagName).toBe("BUTTON");
        });

        it("applies the base classes", () => {
            const wrapper = mountBadge();

            expect(wrapper.classes()).toContain("inline-flex");
            expect(wrapper.classes()).toContain("items-center");
            expect(wrapper.classes()).toContain("text-xs");
            expect(wrapper.classes()).toContain("font-medium");
            expect(wrapper.classes()).toContain("inset-ring");
        });
    });

    describe("text", () => {
        it("renders the text prop", () => {
            const wrapper = mountBadge({
                props: {
                    text: "Save",
                },
            });

            expect(wrapper.text()).toContain("Save");
        });

        it("renders slot content instead of text", () => {
            const wrapper = mountBadge({
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
            const wrapper = mountBadge({
                props: {
                    icon: "check",
                },
            });

            const icon = wrapper.find(".fa-icon-stub");

            expect(icon.exists()).toBe(true);
            expect(icon.attributes("data-icon")).toBe("check");
        });

        it("does not render an icon when one is not provided", () => {
            const wrapper = mountBadge();

            expect(wrapper.findComponent({ name: "fa-icon" }).exists()).toBe(
                false,
            );
        });

        it("renders icon fallback content when no slot is provided", () => {
            const wrapper = mountBadge({
                props: {
                    icon: "save",
                    text: "Save",
                },
            });

            expect(wrapper.text()).toContain("Save");
        });

        it("does not render the icon when slot content is provided", () => {
            const wrapper = mountBadge({
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
            ["sm", "px-1 py-1"],
            ["md", "px-2 py-1"],
            ["lg", "px-3 py-4"],
        ])("applies the correct classes for size %s", (size, expectedClass) => {
            const wrapper = mountBadge({
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
            const wrapper = mountBadge();

            expect(wrapper.classes()).toContain("px-2");
            expect(wrapper.classes()).toContain("py-1");
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
                const wrapper = mountBadge({
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
            const wrapper = mountBadge();

            expect(wrapper.classes()).toContain("bg-blue-500");
            expect(wrapper.classes()).toContain("text-white");
            expect(wrapper.classes()).toContain("focus:outline-blue-600");
        });
    });

    describe("circle", () => {
        it("applies circle styling", () => {
            const wrapper = mountBadge({
                props: {
                    circle: true,
                },
            });

            expect(wrapper.classes()).toContain("rounded-full");
        });

        it("does not apply circle styling by default", () => {
            const wrapper = mountBadge();

            expect(wrapper.classes()).not.toContain("rounded-full!");
        });
    });

    describe("href", () => {
        it("renders a button when href is not provided", () => {
            const wrapper = mountBadge();

            expect(wrapper.element.tagName).toBe("BUTTON");
        });

        it("renders an Inertia Link when href is provided", () => {
            const wrapper = mountBadge({
                props: {
                    href: "/users",
                },
            });

            expect(wrapper.findComponent(Link).exists()).toBe(true);
        });

        it("passes href to the link", () => {
            const wrapper = mountBadge({
                props: {
                    href: "/users",
                },
            });

            const link = wrapper.findComponent(Link);

            expect(link.props("href")).toBe("/users");
        });
    });
});
