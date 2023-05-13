import type { Ref, ComputedRef, InjectionKey } from "vue";
import type {
    customerPermissionType,
    customerType,
} from "@/Types";

export const customerKey: InjectionKey<Ref<customerType>> = Symbol();
export const custPermissionsKey: InjectionKey<customerPermissionType> =
    Symbol();
export const isCustFavKey: InjectionKey<boolean> = Symbol();
export const allowShareKey: InjectionKey<ComputedRef<boolean>> = Symbol();
export const phoneTypesKey: InjectionKey<Ref<string[]>> = Symbol();

/**
 * Loading state managers
 */
export const toggleManageLoadKey: InjectionKey<(set: boolean) => boolean> =
    Symbol();
export const toggleEquipLoadKey: InjectionKey<() => void> = Symbol();
export const toggleContactsLoadKey: InjectionKey<() => void> = Symbol();
export const toggleNotesLoadKey: InjectionKey<() => void> = Symbol();
export const toggleFilesLoadKey: InjectionKey<() => void> = Symbol();
