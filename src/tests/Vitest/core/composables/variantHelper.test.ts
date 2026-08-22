import { useVariantHelper } from "@/core/composables/variantHelper";

describe("useVariantHelper", () => {
    describe("getVariantBase", () => {
        const { getVariantBase } = useVariantHelper();

        it.each([
            ["danger", "rose-600"],
            ["dark", "gray-900"],
            ["error", "red-500"],
            ["help", "violet-600"],
            ["info", "blue-400"],
            ["light", "neutral-300"],
            ["primary", "blue-500"],
            ["secondary", "blue-300"],
            ["success", "green-500"],
            ["warning", "yellow-400"],
            ["none", ""],
        ] as const)("maps %s to %s", (variant, expected) => {
            expect(getVariantBase(variant)).toBe(expected);
        });
    });

    describe("getBackgroundClass", () => {
        const { getBackgroundClass } = useVariantHelper();

        it.each([
            ["danger", "bg-rose-600"],
            ["dark", "bg-gray-900"],
            ["error", "bg-red-500"],
            ["help", "bg-violet-600"],
            ["info", "bg-blue-400"],
            ["light", "bg-neutral-300"],
            ["primary", "bg-blue-500"],
            ["secondary", "bg-blue-300"],
            ["success", "bg-green-500"],
            ["warning", "bg-yellow-400"],
            ["none", ""],
        ] as const)("maps %s to %s", (variant, expected) => {
            expect(getBackgroundClass(variant)).toBe(expected);
        });
    });

    describe("getVariantClass", () => {
        const { getVariantClass } = useVariantHelper();

        it.each([
            ["danger", "bg-rose-600 text-white focus:outline-rose-700"],
            ["dark", "bg-gray-900 text-white focus:outline-gray-900"],
            ["error", "bg-red-500 text-white focus:outline-red-860"],
            ["help", "bg-violet-600 text-white focus:outline-violet-700"],
            ["info", "bg-blue-400 text-white focus:outline-blue-500"],
            ["light", "bg-neutral-300 focus:outline-nuetral-400"],
            ["primary", "bg-blue-500 text-white focus:outline-blue-600"],
            ["secondary", "bg-blue-300 focus:outline-blue-400"],
            ["success", "bg-green-500 text-white focus:outline-green-600"],
            ["warning", "bg-yellow-400 focus:outline-yellow-500"],
            ["none", ""],
        ] as const)("maps %s to %s", (variant, expected) => {
            expect(getVariantClass(variant)).toBe(expected);
        });
    });

    describe("getActiveVariantClass", () => {
        const { getActiveVariantClass } = useVariantHelper();

        it.each([
            ["danger", "bg-rose-800"],
            ["dark", "bg-gray-900"],
            ["error", "bg-red-700"],
            ["help", "bg-violet-800"],
            ["info", "bg-blue-600"],
            ["light", "bg-neutral-500"],
            ["primary", "bg-blue-700"],
            ["secondary", "bg-blue-500"],
            ["success", "bg-green-700"],
            ["warning", "bg-yellow-600"],
            ["none", "bg-blue-100"],
        ] as const)("maps %s to %s", (variant, expected) => {
            expect(getActiveVariantClass(variant)).toBe(expected);
        });
    });

    describe("getVariantIcon", () => {
        const { getVariantIcon } = useVariantHelper();

        it.each([
            ["danger", "exclamation-circle"],
            ["dark", "bell"],
            ["error", "bug"],
            ["help", "circle-question"],
            ["info", "bell"],
            ["light", "bell"],
            ["primary", "bell"],
            ["secondary", "bell"],
            ["success", "circle-check"],
            ["warning", "triangle-exclamation"],
            ["none", ""],
        ] as const)("maps %s to %s", (variant, expected) => {
            expect(getVariantIcon(variant)).toBe(expected);
        });
    });
});
