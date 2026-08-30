import SkeletonLoader from "@/core/components/loaders/SkeletonLoader.vue";
import { describe, expect, it } from "vitest";
import { DOMWrapper, mount } from "@vue/test-utils";

describe("SkeletonLoader", () => {
    const mountLoader = (options = {}) => {
        return mount(SkeletonLoader, {
            ...options,
        });
    };

    it("reders by default", () => {
        const wrapper = mountLoader();

        const loader = wrapper.find(".skeleton");

        expect(loader.exists()).toBe(true);
    });

    it("renders proper width when provided", () => {
        const wrapper = mountLoader({
            props: {
                width: "50",
            },
        });

        const skel: DOMWrapper<HTMLElement> = wrapper.find(".bg-gray-300");

        expect(skel.element.style.width).toBe("50%");
    });
});
