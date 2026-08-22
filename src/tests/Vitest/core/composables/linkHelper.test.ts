import { router } from "@inertiajs/vue3";
import { useLinkHelper } from "@/core/composables/linkHelper";

vi.mock("@inertiajs/vue3", () => ({
    router: {
        visit: vi.fn(),
    },
}));

describe("useLinkHelper", () => {
    const link = {
        href: "/customers/123",
        external: false,
    };

    beforeEach(() => {
        vi.clearAllMocks();

        vi.spyOn(window, "open").mockImplementation(() => null);
    });

    it.each([
        ["middle click", { button: 1 }],
        ["Ctrl + click", { ctrlKey: true }],
        ["Cmd + click", { metaKey: true }],
        ["Shift + click", { shiftKey: true }],
    ])("opens a new tab for %s", (_, eventOptions) => {
        const event = new MouseEvent("click", eventOptions);

        useLinkHelper(event, link);

        expect(window.open).toHaveBeenCalledWith(link.href, "_blank");

        expect(router.visit).not.toHaveBeenCalled();
    });

    it("uses normal navigation for external links", () => {
        const event = new MouseEvent("click");

        const externalLink = {
            href: "https://example.com",
            external: true,
        };

        useLinkHelper(event, externalLink);

        expect(window.location.href).toBe("https://example.com/");
    });

    it("uses Inertia routing for internal links", () => {
        const event = new MouseEvent("click");

        useLinkHelper(event, link);

        expect(router.visit).toHaveBeenCalledWith(link.href);
    });
});
