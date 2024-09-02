import { gsap } from "gsap/gsap-core";

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
                duration: 0.6,
            }
        )
        .fromTo(
            el,
            {
                opacity: 0,
            },
            {
                opacity: 1,
                duration: 0.4,
                onComplete: done,
            }
        );
};

export const shrinkHide = (el: Element, done: () => void) => {
    let timeline = gsap.timeline();

    timeline
        .to(el, {
            opacity: 0,
            duration: 0.4,
        })
        .to(el, {
            height: 0,
            duration: 0.6,
            onComplete: done,
        });
};
