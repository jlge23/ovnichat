import { ReactNode } from "react";

export default function Card({
    children,
    className = "",
}: {
    children: ReactNode;
    className?: string;
}) {
    return (
        <div
            className={`w-full h-full border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-500 drop-shadow-gray-300 dark:drop-shadow-gray-800 drop-shadow-lg ${className}`}
        >
            {children}
        </div>
    );
}
