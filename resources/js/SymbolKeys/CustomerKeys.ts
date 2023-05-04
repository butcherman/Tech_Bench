import type { Ref, InjectionKey } from "vue";
import type { customerPermissionType, customerType } from "@/Types";

export const custPermissionsKey: InjectionKey<customerPermissionType> =
    Symbol();
export const isCustFavKey: InjectionKey<boolean> = Symbol();
export const customerKey: InjectionKey<Ref<customerType>> = Symbol();
export const toggleManageLoadKey: InjectionKey<(set: boolean) => boolean> =
    Symbol();
