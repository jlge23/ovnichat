const icons = {
    plus: {
        d: "M16,10c0,0.553-0.048,1-0.601,1H11v4.399C11,15.951,10.553,16,10,16c-0.553,0-1-0.049-1-0.601V11H4.601 C4.049,11,4,10.553,4,10c0-0.553,0.049-1,0.601-1H9V4.601C9,4.048,9.447,4,10,4c0.553,0,1,0.048,1,0.601V9h4.399 C15.952,9,16,9.447,16,10z",
        viewBox: "0 0 20 20",
    },
    save: {
        d: "M840-680v480q0 33-23.5 56.5T760-120H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h480l160 160Zm-80 34L646-760H200v560h560v-446ZM480-240q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35ZM240-560h360v-160H240v160Zm-40-86v446-560 114Z",
        viewBox: "0 -960 960 960",
    },
} as const;

export default function FAB({
    icon = "plus",
    disabled = false,
    onClick,
}: {
    icon?: "plus" | "save";
    disabled?: boolean;
    onClick?: () => void;
}) {
    const iconData = icons[icon];

    return (
        <div
            className="fixed bottom-10 right-10"
            onClick={() => !disabled && onClick && onClick()}
        >
            <button
                className={`p-0 size-18 ${
                    disabled ? "bg-purple-300" : "bg-purple-500"
                } rounded-full hover:bg-purple-700 cursor-pointer active:shadow-lg mouse shadow transition ease-in duration-200 focus:outline-none`}
            >
                <svg
                    viewBox={iconData.viewBox}
                    enableBackground={`new ${iconData.viewBox}`}
                    className="size-9 inline-block"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="#FFFFFF"
                >
                    <path d={iconData.d} />
                </svg>
            </button>
        </div>
    );
}
