import { gsap } from "gsap";

export const shake = (el: Element) => {
    gsap.fromTo(
        el,
        { x: -2 },
        { x: 2, clearProps: "x", repeat: 4, duration: 0.1, ease: "rough" }
    );
};

export const grow = (el: Element) => {
    console.log('called grow');
    gsap.from(
        el,
        { height:0  },
    )
}

// export const fadeOut = (el: Element) => {
//     console.log('called fade out');

//     gsap.to(el, {
//         opacity: 0,
//         // delay: 0.5,
//         duration: 10,
//     });
// }

// export const fadeIn = (el: Element) => {
//     console.log('called fade in');
//     console.log(el);

//     // gsap.to(el, {
//     //     opacity: 1,
//     //     delay: 0.5,
//     //     duration: 3,
//     // });
// }

// export const fadeOutIn = (el: Element) => {
//     gsap.to(el, {
//         opacity: 0,
//         delay: 0.5,
//         duration: 10,
//         onComplete: () => {
//             gsap.to(el, {
//                 opacity: 1,
//                 delay: 0.5,
//                 duration: 10,
//             });
//         }
//     });
// }
