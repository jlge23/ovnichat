import React from "react";

type Variant =
    | "default"
    | "alternative"
    | "dark"
    | "light"
    | "green"
    | "red"
    | "yellow"
    | "purple";

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
    variant?: Variant;
}

const baseStyles: Record<Variant, string> = {
    default:
        "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800",
    alternative:
        "text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700",
    dark: "text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:ring-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700",
    light: "text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700",
    green: "text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800",
    red: "text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900",
    yellow: "text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 dark:focus:ring-yellow-900",
    purple: "text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900",
};

const disabledStyles: Record<Variant, string> = {
    default: "bg-blue-300 text-white cursor-not-allowed",
    alternative: "bg-gray-200 text-gray-400 border-gray-300 cursor-not-allowed",
    dark: "bg-gray-500 text-white cursor-not-allowed",
    light: "bg-gray-200 text-gray-400 cursor-not-allowed",
    green: "bg-green-300 text-white cursor-not-allowed",
    red: "bg-red-300 text-white cursor-not-allowed",
    yellow: "bg-yellow-300 text-white cursor-not-allowed",
    purple: "bg-purple-300 text-white cursor-not-allowed",
};

export const Button: React.FC<ButtonProps> = ({
    children,
    variant = "default",
    disabled,
    className = "",
    ...props
}) => {
    const colorClass = disabled ? disabledStyles[variant] : baseStyles[variant];

    const fullClass = [
        "font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none w-full",
        colorClass,
        className,
    ]
        .filter(Boolean)
        .join(" ");

    return (
        <button
            type="button"
            disabled={disabled}
            className={fullClass}
            {...props}
        >
            {children}
        </button>
    );
};
