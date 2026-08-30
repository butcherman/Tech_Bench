import EllipsisLoader from "@/core/components/loaders/EllipsisLoader.vue";
import { describe, expect, it } from "vitest";
import { mount } from "@vue/test-utils";

describe("EllipsisLoader", () => {
    const mountLoader = (options = {}) => {
        return mount(EllipsisLoader, {
            ...options,
        });
    };

    it("reders by default", () => {
        const wrapper = mountLoader();

        const loader = wrapper.find(".loading");

        expect(loader.exists()).toBe(true);
    });

    it("does not have text by default", () => {
        const wrapper = mountLoader();

        const textHeader = wrapper.find(".loading");

        expect(textHeader.text()).toBe("");
    });

    it("renders text when provided", () => {
        const wrapper = mountLoader({
            props: {
                text: "Loading Text",
            },
        });

        const textHeader = wrapper.find(".loading");

        console.log(textHeader.text());

        expect(textHeader.exists()).toBe(true);
        expect(textHeader.text()).toBe("Loading Text");
    });
});
