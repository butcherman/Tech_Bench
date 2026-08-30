import HollowDotsLoader from "@/core/components/loaders/HollowDotsLoader.vue";
import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";

describe("HollowDotsLoader", () => {
    const mountLoader = (options = {}) => {
        return mount(HollowDotsLoader, {
            ...options,
        });
    };

    it("reders by default", () => {
        const wrapper = mountLoader();

        const loader = wrapper.find(".hollow-dots-spinner");

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
