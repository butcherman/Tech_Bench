import gsap from "gsap";

/**
 * Increase the height of the element, then increase the opacity.
 */
export const growShow = (el: Element, done: () => void) => {
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
            }
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
            }
        );
};

/**
 * Fade out via opacity, then bring height to 0 before exiting
 */
export const shrinkHide = (el: Element, done: () => void) => {
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
