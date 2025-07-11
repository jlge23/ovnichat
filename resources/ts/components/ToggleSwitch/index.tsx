import "./ToggleSwitch.css";

type ToggleSwitchProps = {
    name: string;
    checked: boolean;
    onChange: (value: boolean) => void;
    label?: string;
    darkMode?: boolean;
};

export default function ToggleSwitch({
    name,
    checked,
    onChange,
    label,
    darkMode = true,
}: ToggleSwitchProps) {
    return (
        <>
            {label && (
                <span
                    className={`block w-full mb-2 text-sm text-gray-700 ${
                        darkMode ? "dark:text-gray-300" : ""
                    }`}
                >
                    {label}
                </span>
            )}
            <label className="switch w-full">
                <input
                    type="checkbox"
                    name={name}
                    checked={checked}
                    onChange={(e) => onChange(e.target.checked)}
                />
                <span className="slider"></span>
            </label>
        </>
    );
}
