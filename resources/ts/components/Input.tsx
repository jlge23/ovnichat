import { InputHTMLAttributes } from "react";

type InputProps = InputHTMLAttributes<HTMLInputElement> & {
    label?: string;
    errorMessage?: string;
    errorActive?: boolean;
    darkMode?: boolean;
};

export default function Input({
    label,
    id,
    name,
    value,
    required = false,
    placeholder = "",
    type = "text",
    onChange,
    errorMessage = "",
    errorActive = false,
    darkMode = true,
}: InputProps) {
    return (
        <div className="mb-3">
            {label ? (
                <label
                    htmlFor={id}
                    className={`block mb-2 text-sm font-medium text-gray-900 ${
                        darkMode ? "dark:text-white" : ""
                    }`}
                >
                    {label}
                </label>
            ) : null}
            <input
                type={type}
                name={name}
                id={id}
                className={`${
                    errorActive
                        ? `bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500 ${
                              darkMode
                                  ? "dark:bg-gray-700 dark:border-red-500 dark:text-red-500 dark:placeholder-red-500"
                                  : ""
                          }`
                        : `bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 ${
                              darkMode
                                  ? "dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                  : ""
                          }`
                } text-sm rounded-lg block w-full p-2.5`}
                placeholder={placeholder}
                required={required}
                value={value}
                onChange={onChange}
            />

            {errorActive ? (
                <p
                    className={`text-sm text-red-700 font-semibold ${
                        darkMode ? "dark:text-red-500" : ""
                    }`}
                >
                    {errorMessage}
                </p>
            ) : null}
        </div>
    );
}
