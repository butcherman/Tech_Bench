import gsap from "gsap";

/*
|-------------------------------------------------------------------------------
| Table Transition Animations
|-------------------------------------------------------------------------------
*/
export const fadeOut = (el: Element): void => {
    gsap.to(el, {
        opacity: 0,
        duration: 0.8,
    });
};

export const fadeIn = (el: Element, done: () => void): void => {
    gsap.from(el, {
        opacity: 0,
        duration: 0.8,
        onComplete: done,
    });
};
