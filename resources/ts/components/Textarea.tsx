type TextareaProps = {
    label?: string;
    name: string;
    value: string;
    onChange: (e: React.ChangeEvent<HTMLTextAreaElement>) => void;
    errorMessage?: string;
    errorActive?: boolean;
    required?: boolean;
    disabled?: boolean;
    darkMode?: boolean;
};

export default function Textarea({
    label,
    name,
    value,
    onChange,
    errorMessage,
    errorActive = false,
    required = false,
    disabled = false,
    darkMode = true,
}: TextareaProps) {
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
            <textarea
                id={name}
                name={name}
                value={value}
                onChange={onChange}
                required={required}
                disabled={disabled}
                className={`w-full rounded-lg p-2.5 text-sm border resize-none min-h-[100px] ${
                    errorActive
                        ? `border-red-500 bg-red-50 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500 ${
                              darkMode
                                  ? "dark:bg-gray-700 dark:border-red-500 dark:text-red-500"
                                  : ""
                          }`
                        : `border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 ${
                              darkMode
                                  ? "dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                  : ""
                          }`
                }`}
            />
            {errorActive && errorMessage && (
                <p
                    className={`text-sm text-red-700 font-semibold ${
                        darkMode ? "dark:text-red-500" : ""
                    } mt-1`}
                >
                    {errorMessage}
                </p>
            )}
        </div>
    );
}
