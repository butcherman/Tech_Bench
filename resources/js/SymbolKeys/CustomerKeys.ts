import type { Ref, ComputedRef, InjectionKey } from "vue";

export const customerSearchDataKey:InjectionKey<customerSearchDataSymbol> = Symbol();

export const customerKey: InjectionKey<Ref<customer>> = Symbol();
export const custPermissionsKey: InjectionKey<customerPermissions> =
    Symbol();
export const isCustFavKey: InjectionKey<boolean> = Symbol();
export const allowShareKey: InjectionKey<ComputedRef<boolean>> = Symbol();
export const fileTypesKey: InjectionKey<string[]> = Symbol();
export const equipTypesKey: InjectionKey<categoryList[]> = Symbol();
export const phoneTypesKey: InjectionKey<phoneNumber[]> = Symbol();

/**
 * Loading state managers
 */
export const toggleManageLoadKey: InjectionKey<(set: boolean) => void> =
    Symbol();
export const toggleEquipLoadKey: InjectionKey<() => void> = Symbol();
export const toggleContactsLoadKey: InjectionKey<() => void> = Symbol();
export const toggleNotesLoadKey: InjectionKey<() => void> = Symbol();
export const toggleFilesLoadKey: InjectionKey<() => void> = Symbol();
