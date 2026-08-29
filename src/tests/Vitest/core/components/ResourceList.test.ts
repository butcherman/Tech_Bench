import { describe, expect, it, vi } from "vitest";
import { mount } from "@vue/test-utils";
import ResourceList from "@/core/components/ResourceList.vue";

describe("List", () => {
    const mountList = (options = {}) => {
        return mount(ResourceList, {
            ...options,
        });
    };

    describe("rendering", () => {
        it("renders a list of strings", () => {
            const wrapper = mountList({
                props: {
                    list: ["Apple", "Banana", "Orange"],
                },
            });

            const rows = wrapper.findAll("li");

            expect(rows).toHaveLength(3);
            expect(rows[0].text()).toBe("Apple");
            expect(rows[1].text()).toBe("Banana");
            expect(rows[2].text()).toBe("Orange");
        });

        it("renders object data using textField", () => {
            const wrapper = mountList({
                props: {
                    list: [
                        { id: 1, name: "Apple" },
                        { id: 2, name: "Banana" },
                    ],
                    textField: "name",
                },
            });

            const rows = wrapper.findAll("li");

            expect(rows).toHaveLength(2);
            expect(rows[0].text()).toBe("Apple");
            expect(rows[1].text()).toBe("Banana");
        });

        it("renders the string representation of an object without textField", () => {
            const wrapper = mountList({
                props: {
                    list: [{ name: "Apple" }],
                },
            });

            const row = wrapper.find("li");

            expect(row.text()).toBe("[object Object]");
        });

        it("renders empty text when the list is empty", () => {
            const wrapper = mountList({
                props: {
                    list: [],
                },
            });

            expect(wrapper.find("li").text()).toBe("No Data");
        });

        it("renders custom empty text", () => {
            const wrapper = mountList({
                props: {
                    list: [],
                    emptyText: "Nothing to display",
                },
            });

            expect(wrapper.find("li").text()).toBe("Nothing to display");
        });

        it("does not render rows when the list is empty", () => {
            const wrapper = mountList({
                props: {
                    list: [],
                },
            });

            expect(wrapper.findAll("li")).toHaveLength(1);
            expect(wrapper.findAll("li")[0].text()).toBe("No Data");
        });
    });

    describe("styling", () => {
        it("uses normal padding by default", () => {
            const wrapper = mountList({
                props: {
                    list: ["Apple"],
                },
            });

            expect(wrapper.find("li").classes()).toContain("p-3");
        });

        it("uses compact padding when compact is true", () => {
            const wrapper = mountList({
                props: {
                    list: ["Apple"],
                    compact: true,
                },
            });

            expect(wrapper.find("li").classes()).toContain("p-1");
            expect(wrapper.find("li").classes()).not.toContain("p-3");
        });

        it("does not add hover classes by default", () => {
            const wrapper = mountList({
                props: {
                    list: ["Apple"],
                },
            });

            expect(wrapper.find("li").classes()).not.toContain("pointer");
            expect(wrapper.find("li").classes()).not.toContain(
                "hover:bg-slate-200",
            );
        });

        it("adds hover classes when hoverRow is true", () => {
            const wrapper = mountList({
                props: {
                    list: ["Apple"],
                    hoverRow: true,
                },
            });

            expect(wrapper.find("li").classes()).toContain("pointer");
            expect(wrapper.find("li").classes()).toContain(
                "hover:bg-slate-200",
            );
        });

        it("does not add border classes by default", () => {
            const wrapper = mountList({
                props: {
                    list: ["Apple"],
                },
            });

            expect(wrapper.find("ul").classes()).not.toContain("border");
            expect(wrapper.find("li").classes()).not.toContain("border-b");
        });

        it("adds the main border classes when hasBorder is true", () => {
            const wrapper = mountList({
                props: {
                    list: ["Apple"],
                    hasBorder: true,
                },
            });

            const ul = wrapper.find("ul");

            expect(ul.classes()).toContain("border");
            expect(ul.classes()).toContain("border-slate-200");
            expect(ul.classes()).toContain("rounded-lg");
        });

        it("adds row border classes when hasBorder is true", () => {
            const wrapper = mountList({
                props: {
                    list: ["Apple", "Banana"],
                    hasBorder: true,
                },
            });

            const rows = wrapper.findAll("li");

            expect(rows[0].classes()).toContain("border-b");
            expect(rows[0].classes()).toContain("border-b-slate-300");
            expect(rows[1].classes()).toContain("border-b");
            expect(rows[1].classes()).toContain("border-b-slate-300");
        });

        it.each([
            ["start", "text-left"],
            ["end", "text-right"],
            ["center", "text-center"],
        ])("uses the %s text position", (textPosition, expectedClass) => {
            const wrapper = mountList({
                props: {
                    list: ["Apple"],
                    textPosition,
                },
            });

            expect(wrapper.find("li").classes()).toContain(expectedClass);
        });

        it("uses left alignment by default", () => {
            const wrapper = mountList({
                props: {
                    list: ["Apple"],
                },
            });

            expect(wrapper.find("li").classes()).toContain("text-left");
        });
    });

    describe("slots", () => {
        it("renders the list-item slot", () => {
            const wrapper = mountList({
                props: {
                    list: ["Apple", "Banana"],
                },
                slots: {
                    "list-item": "<span class='custom-item'>Custom Item</span>",
                },
            });

            expect(wrapper.findAll(".custom-item")).toHaveLength(2);
            expect(wrapper.findAll(".custom-item")[0].text()).toBe(
                "Custom Item",
            );
        });

        it("passes the indexed item to the list-item slot", () => {
            const wrapper = mountList({
                props: {
                    list: ["Apple"],
                },
                slots: {
                    "list-item": `
                        <template #list-item="{ item }">
                            <span>{{ item.data }} - {{ item.id }}</span>
                        </template>
                    `,
                },
            });

            expect(wrapper.find("li").text()).toContain("Apple");
        });

        it("renders the actions slot", () => {
            const wrapper = mountList({
                props: {
                    list: ["Apple"],
                },
                slots: {
                    actions: "<button class='action'>Edit</button>",
                },
            });

            expect(wrapper.find(".action").exists()).toBe(true);
            expect(wrapper.find(".action").text()).toBe("Edit");
        });

        it("renders the empty slot instead of default empty text", () => {
            const wrapper = mountList({
                props: {
                    list: [],
                    emptyText: "Should not be shown",
                },
                slots: {
                    "empty-slot": "<div class='custom-empty'>No items!</div>",
                },
            });

            expect(wrapper.find(".custom-empty").exists()).toBe(true);
            expect(wrapper.find(".custom-empty").text()).toBe("No items!");
            expect(wrapper.text()).not.toContain("Should not be shown");
        });
    });

    describe("row clicks", () => {
        it("emits rowClicked when a row is clicked", async () => {
            const wrapper = mountList({
                props: {
                    list: ["Apple"],
                },
            });

            await wrapper.find("li").trigger("click");

            expect(wrapper.emitted("rowClicked")).toHaveLength(1);
        });

        it("emits the mouse event and original item", async () => {
            const item = "Apple";

            const wrapper = mountList({
                props: {
                    list: [item],
                },
            });

            await wrapper.find("li").trigger("click");

            const emitted = wrapper.emitted("rowClicked")?.[0];

            expect(emitted).toHaveLength(2);
            expect(emitted?.[0]).toBeInstanceOf(MouseEvent);
            expect(emitted?.[1]).toBe(item);
        });

        it("calls rowClickFn when a row is clicked", async () => {
            const rowClickFn = vi.fn();
            const item = "Apple";

            const wrapper = mountList({
                props: {
                    list: [item],
                    rowClickFn,
                },
            });

            await wrapper.find("li").trigger("click");

            expect(rowClickFn).toHaveBeenCalledTimes(1);
            expect(rowClickFn).toHaveBeenCalledWith(
                expect.any(MouseEvent),
                item,
            );
        });

        it("both emits rowClicked and calls rowClickFn", async () => {
            const rowClickFn = vi.fn();
            const item = "Apple";

            const wrapper = mountList({
                props: {
                    list: [item],
                    rowClickFn,
                },
            });

            await wrapper.find("li").trigger("click");

            expect(wrapper.emitted("rowClicked")).toHaveLength(1);
            expect(rowClickFn).toHaveBeenCalledTimes(1);
        });

        it("passes the correct item when a specific row is clicked", async () => {
            const rowClickFn = vi.fn();

            const wrapper = mountList({
                props: {
                    list: ["Apple", "Banana", "Orange"],
                    rowClickFn,
                },
            });

            const rows = wrapper.findAll("li");

            await rows[1].trigger("click");

            expect(rowClickFn).toHaveBeenCalledWith(
                expect.any(MouseEvent),
                "Banana",
            );

            expect(wrapper.emitted("rowClicked")?.[0]?.[1]).toBe("Banana");
        });
    });
});
