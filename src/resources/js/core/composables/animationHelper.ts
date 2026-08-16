import gsap from "gsap";

export const useAnimationHelper = () => {
    /**
     * Increase the height of the element, then increase the opacity.
     */
    const growShow = (el: Element, done: () => void) => {
        let timeline = gsap.timeline();

        timeline
            .fromTo(
                el,
                {
                    height: 0,
                },
                {
                    height: "auto",
                    duration: 0.3,
                },
            )
            .fromTo(
                el,
                {
                    opacity: 0,
                },
                {
                    opacity: 1,
                    duration: 0.2,
                    onComplete: done,
                },
            );
    };

    /**
     * Fade out via opacity, then bring height to 0 before exiting
     */
    const shrinkHide = (el: Element, done: () => void) => {
        let timeline = gsap.timeline();

        timeline
            .to(el, {
                opacity: 0,
                duration: 0.2,
            })
            .to(el, {
                height: 0,
                duration: 0.3,
                onComplete: done,
            });
    };

    /**
     * Set the Opacity of the component to 0 for fade in
     */
    const beforeFadeIn = (el: Element) => {
        gsap.set(el, { opacity: 0 });
    };

    /**
     * Fade in via opacity
     */
    const fadeIn = (el: Element, done?: () => void) => {
        gsap.fromTo(
            el,
            {
                opacity: 0,
            },
            {
                opacity: 1,
                duration: 0.2,
                onComplete: done,
            },
        );
    };

    /**
     * Fade out via opacity
     */
    const fadeOut = (el: Element, done?: () => void) => {
        gsap.fromTo(
            el,
            {
                opacity: 1,
            },
            {
                opacity: 0,
                duration: 0.2,
                onComplete: done,
            },
        );
    };

    return {
        growShow,
        shrinkHide,
        beforeFadeIn,
        fadeIn,
        fadeOut,
    };
};
