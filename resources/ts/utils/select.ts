import { SelectOption } from "@/components/SelectField";

export function formatToSelect<T extends Record<string, any>>(
    list: T[],
    valueKey: keyof T,
    labelKey: keyof T
): SelectOption[] {
    return list.map((item) => ({
        value: item[valueKey] as string | number,
        label: String(item[labelKey]),
    }));
}
