import { beforeEach, describe, expect, it, vi } from "vitest";

const { gsapMock, timelineMock } = vi.hoisted(() => {
    const timelineMock = {
        fromTo: vi.fn().mockReturnThis(),
        to: vi.fn().mockReturnThis(),
    };

    const gsapMock = {
        timeline: vi.fn(() => timelineMock),
        set: vi.fn(),
        fromTo: vi.fn(),
    };

    return {
        gsapMock,
        timelineMock,
    };
});

vi.mock("gsap", () => ({
    default: gsapMock,
}));

import { useAnimationHelper } from "@/core/composables/animationHelper";

describe("useAnimationHelper", () => {
    beforeEach(() => {
        vi.clearAllMocks();

        timelineMock.fromTo.mockReturnThis();
        timelineMock.to.mockReturnThis();

        gsapMock.timeline.mockReturnValue(timelineMock);
    });

    describe("growShow", () => {
        it("grows the element and then shows it", () => {
            const { growShow } = useAnimationHelper();
            const element = document.createElement("div");
            const done = vi.fn();

            growShow(element, done);

            expect(gsapMock.timeline).toHaveBeenCalledOnce();

            expect(timelineMock.fromTo).toHaveBeenNthCalledWith(
                1,
                element,
                {
                    height: 0,
                },
                {
                    height: "auto",
                    duration: 0.3,
                },
            );

            expect(timelineMock.fromTo).toHaveBeenNthCalledWith(
                2,
                element,
                {
                    opacity: 0,
                },
                {
                    opacity: 1,
                    duration: 0.2,
                    onComplete: done,
                },
            );
        });
    });

    describe("shrinkHide", () => {
        it("hides the element and then shrinks it", () => {
            const { shrinkHide } = useAnimationHelper();
            const element = document.createElement("div");
            const done = vi.fn();

            shrinkHide(element, done);

            expect(gsapMock.timeline).toHaveBeenCalledOnce();

            expect(timelineMock.to).toHaveBeenNthCalledWith(1, element, {
                opacity: 0,
                duration: 0.2,
            });

            expect(timelineMock.to).toHaveBeenNthCalledWith(2, element, {
                height: 0,
                duration: 0.3,
                onComplete: done,
            });
        });
    });

    describe("beforeFadeIn", () => {
        it("sets the element opacity to zero", () => {
            const { beforeFadeIn } = useAnimationHelper();
            const element = document.createElement("div");

            beforeFadeIn(element);

            expect(gsapMock.set).toHaveBeenCalledWith(element, {
                opacity: 0,
            });
        });
    });

    describe("fadeIn", () => {
        it("fades the element in", () => {
            const { fadeIn } = useAnimationHelper();
            const element = document.createElement("div");
            const done = vi.fn();

            fadeIn(element, done);

            expect(gsapMock.fromTo).toHaveBeenCalledWith(
                element,
                {
                    opacity: 0,
                },
                {
                    opacity: 1,
                    duration: 0.2,
                    onComplete: done,
                },
            );
        });
    });

    describe("fadeOut", () => {
        it("fades the element out", () => {
            const { fadeOut } = useAnimationHelper();
            const element = document.createElement("div");
            const done = vi.fn();

            fadeOut(element, done);

            expect(gsapMock.fromTo).toHaveBeenCalledWith(
                element,
                {
                    opacity: 1,
                },
                {
                    opacity: 0,
                    duration: 0.2,
                    onComplete: done,
                },
            );
        });
    });
});
