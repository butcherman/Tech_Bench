import AtomLoader from "@/core/components/loaders/AtomLoader.vue";
import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";

describe("AtomLoader", () => {
    const mountLoader = (options = {}) => {
        return mount(AtomLoader, {
            ...options,
        });
    };

    it("reders by default", () => {
        const wrapper = mountLoader();

        const loader = wrapper.find(".atom-spinner");

        expect(loader.exists()).toBe(true);
    });

    it("does not have text by default", () => {
        const wrapper = mountLoader();

        const textHeader = wrapper.find("h5");

        expect(textHeader.exists()).toBe(false);
    });

    it("renders text when provided", () => {
        const wrapper = mountLoader({
            props: {
                text: "Loading Text",
            },
        });

        const textHeader = wrapper.find("h5");

        expect(textHeader.exists()).toBe(true);
        expect(textHeader.text()).toBe("Loading Text");
    });
});
