import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";
import Overlay from "@/core/components/Overlay.vue";
import AtomLoader from "@/core/components/loaders/AtomLoader.vue";

describe("LoadingOverlay", () => {
    it("renders", () => {
        const wrapper = mount(Overlay, {
            props: {
                loading: false,
            },
        });

        expect(wrapper.exists()).toBe(true);
    });

    it("renders the default loader", () => {
        const wrapper = mount(Overlay, {
            props: {
                loading: true,
            },
        });

        expect(wrapper.findComponent(AtomLoader).exists()).toBe(true);
    });

    it("passes loadingText to the default loader", () => {
        const wrapper = mount(Overlay, {
            props: {
                loading: true,
                loadingText: "Loading customer...",
            },
        });

        const loader = wrapper.findComponent(AtomLoader);

        expect(loader.props("text")).toBe("Loading customer...");
    });

    it("renders the default slot", () => {
        const wrapper = mount(Overlay, {
            props: {
                loading: false,
            },
            slots: {
                default: "<div class='content'>Hello World</div>",
            },
        });

        expect(wrapper.find(".content").text()).toBe("Hello World");
    });

    it("renders a custom loader slot instead of the default loader", () => {
        const wrapper = mount(Overlay, {
            props: {
                loading: true,
            },
            slots: {
                loader: "<div class='custom-loader'>Please wait...</div>",
            },
        });

        expect(wrapper.find(".custom-loader").exists()).toBe(true);
        expect(wrapper.findComponent(AtomLoader).exists()).toBe(false);
    });
});
