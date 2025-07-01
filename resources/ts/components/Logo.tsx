import { useEffect, useState } from "react";

export default function Logo({ className }: { className?: string }) {
    const [isDarkMode, setIsDarkMode] = useState(false);

    useEffect(() => {
        const match = window.matchMedia("(prefers-color-scheme: dark)");
        setIsDarkMode(match.matches);

        const listener = (e: MediaQueryListEvent) => setIsDarkMode(e.matches);
        match.addEventListener("change", listener);

        return () => match.removeEventListener("change", listener);
    }, []);

    return (
        <div className={className}>
            <img
                src={`/images/${!isDarkMode ? "logo.png" : "logo-light.png"}`}
                alt="Logo"
                className="w-full h-full object-contain"
            />
        </div>
    );
}
