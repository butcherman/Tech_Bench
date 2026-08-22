import MenuList from "@/core/components/MenuList.vue";
import { mount } from "@vue/test-utils";
import { describe, expect, it } from "vitest";

const LinkStub = {
    props: ["href"],
    template: '<a :href="href"><slot /></a>',
};

const FaIconStub = {
    props: ["icon"],
    template: '<span class="fa-icon" />',
};

const sampleListMenuItem: MenuItem[] = [
    {
        label: "Users",
        icon: "users",
        route: "/users",
    },
    {
        label: "Settings",
        icon: "gear",
        route: "/settings",
    },
];

describe("MenuList", () => {
    it("renders", () => {
        const wrapper = mount(MenuList, {
            props: {
                menuList: sampleListMenuItem,
            },
            global: {
                stubs: {
                    "fa-icon": FaIconStub,
                    Link: LinkStub,
                },
            },
        });

        expect(wrapper.exists()).toBe(true);
    });

    it("renders all menu items", () => {
        const menuList = sampleListMenuItem;

        const wrapper = mount(MenuList, {
            props: { menuList },
            global: {
                stubs: {
                    "fa-icon": FaIconStub,
                    Link: LinkStub,
                },
            },
        });

        expect(wrapper.findAll("li")).toHaveLength(menuList.length);
    });

    it("sets the correct route on each link", () => {
        const menuList = sampleListMenuItem;

        const wrapper = mount(MenuList, {
            props: { menuList },
            global: {
                stubs: {
                    "fa-icon": FaIconStub,
                    Link: LinkStub,
                },
            },
        });

        const links = wrapper.findAllComponents({ name: "Link" });

        links.forEach((link, index) => {
            expect(link.attributes("href")).toBe(menuList[index].route);
        });
    });

    it("renders no menu items when the list is empty", () => {
        const wrapper = mount(MenuList, {
            props: {
                menuList: [],
            },
            global: {
                stubs: {
                    "fa-icon": FaIconStub,
                    Link: LinkStub,
                },
            },
        });

        expect(wrapper.findAll("li")).toHaveLength(0);
    });
});
