import { SelectHTMLAttributes } from "react";

export type SelectOption = {
    value: string | number;
    label: string;
};

type SelectFieldProps = SelectHTMLAttributes<HTMLSelectElement> & {
    label?: string;
    options: SelectOption[];
    errorMessage?: string;
    errorActive?: boolean;
    darkMode?: boolean;
    showDefaultField?: boolean;
};

export default function SelectField({
    label,
    name,
    value,
    onChange,
    options,
    errorMessage,
    errorActive = false,
    required,
    darkMode = true,
    showDefaultField = true,
}: SelectFieldProps) {
    return (
        <div className="mb-3">
            {label && (
                <label
                    htmlFor={name}
                    className={`block mb-2 text-sm font-medium text-gray-900 ${
                        darkMode ? "dark:text-white" : ""
                    }`}
                >
                    {label}
                </label>
            )}
            <select
                id={name}
                name={name}
                value={value}
                onChange={onChange}
                required={required}
                className={`w-full rounded-lg p-2.5 text-sm border ${
                    errorActive
                        ? `border-red-500 bg-red-50 text-red-900 placeholder-red-700 focus:border-red-500 focus:ring-red-500 ${
                              darkMode
                                  ? "dark:bg-gray-700 dark:border-red-500 dark:text-red-500 dark:placeholder-red-500"
                                  : ""
                          }`
                        : `border-gray-300 text-gray-900 focus:border-blue-500 focus:ring-blue-500 ${
                              darkMode
                                  ? "dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                  : ""
                          }`
                }`}
            >
                {showDefaultField ? <option value="">Seleccione</option> : null}
                {options.map((opt) => (
                    <option key={opt.value} value={opt.value}>
                        {opt.label}
                    </option>
                ))}
            </select>
            {errorActive && errorMessage && (
                <p className="text-sm text-red-700 font-semibold mt-1">
                    {errorMessage}
                </p>
            )}
        </div>
    );
}
