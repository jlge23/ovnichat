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
            className={`w-full h-full border border-gray-700 rounded-lg bg-white dark:bg-gray-500 shadow-sm ${className}`}
        >
            {children}
        </div>
    );
}
