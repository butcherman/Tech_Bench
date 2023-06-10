import { gsap } from "gsap";

export const shake = (el: Element) => {
    gsap.fromTo(
        el,
        { x: -2 },
        { x: 2, clearProps: "x", repeat: 4, duration: 0.1, ease: "rough" }
    );
};
