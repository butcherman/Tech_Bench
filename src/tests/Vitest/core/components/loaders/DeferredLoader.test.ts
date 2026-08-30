import DeferredLoader from "@/core/components/loaders/DeferredLoader.vue";
import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";

describe("DeferredLoader", () => {
    const mountDeferredLoader = (options = {}) => {
        return mount(DeferredLoader, {
            global: {
                stubs: {
                    Deferred: {
                        props: ["data"],
                        template: `
                            <div class="deferred">
                                <div class="fallback">
                                    <slot name="fallback" />
                                </div>
                                <div class="rescue">
                                    <slot name="rescue" />
                                </div>
                                <div class="content">
                                    <slot />
                                </div>
                            </div>
                        `,
                    },
                    AtomLoader: {
                        props: ["text"],
                        template: `<div class="atom-loader">{{ text }}</div>`,
                    },
                },
            },
            ...options,
        });
    };

    it("renders the default loader", () => {
        const wrapper = mountDeferredLoader({
            props: {
                data: "users",
            },
        });

        expect(wrapper.find(".atom-loader").exists()).toBe(true);
    });

    it("passes loadingText to the default loader", () => {
        const wrapper = mountDeferredLoader({
            props: {
                data: "users",
                loadingText: "Loading users...",
            },
        });

        expect(wrapper.find(".atom-loader").text()).toBe("Loading users...");
    });

    it("renders a custom loader slot", () => {
        const wrapper = mountDeferredLoader({
            props: {
                data: "users",
            },
            slots: {
                loader: '<div class="custom-loader">Please wait...</div>',
            },
        });

        expect(wrapper.find(".custom-loader").exists()).toBe(true);
        expect(wrapper.find(".atom-loader").exists()).toBe(false);
    });

    it("renders the default error content", () => {
        const wrapper = mountDeferredLoader({
            props: {
                data: "users",
            },
        });

        expect(wrapper.find("img").exists()).toBe(true);
        expect(wrapper.find("img").attributes("src")).toBe(
            "/images/error/oops.png",
        );
        expect(wrapper.find("h4").text()).toBe("Error Loading Data");
    });

    it("renders a custom error slot", () => {
        const wrapper = mountDeferredLoader({
            props: {
                data: "users",
            },
            slots: {
                error: '<div class="custom-error">Something went wrong</div>',
            },
        });

        expect(wrapper.find(".custom-error").exists()).toBe(true);
        expect(wrapper.find("h4").exists()).toBe(false);
    });

    it("renders the default slot", () => {
        const wrapper = mountDeferredLoader({
            props: {
                data: "users",
            },
            slots: {
                default: '<div class="content">User list</div>',
            },
        });

        expect(wrapper.find(".content").text()).toBe("User list");
    });
});
