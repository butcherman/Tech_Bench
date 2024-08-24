import { gsap } from "gsap/gsap-core";

export const growShow = (divId: string) => {
    let timeline = gsap.timeline();

    timeline
        .to(`#${divId}`, {
            height: "auto",
            duration: 0.6,
        })
        .to(`#${divId}`, {
            opacity: 1,
            duration: 0.4,
        });
};

export const shrinkHide = (divId: string) => {
    let timeline = gsap.timeline();

    timeline
        .to(`#${divId}`, {
            opacity: 0,
            duration: 0.4,
        })
        .to(`#${divId}`, {
            height: 0,
            duration: 0.6,
        });
};
