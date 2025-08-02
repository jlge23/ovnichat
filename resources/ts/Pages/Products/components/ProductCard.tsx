import { ProductProps } from "@/api/products";
import { useEffect, useRef, useState } from "react";

export default function ProductCard({
    product,
    onClick,
    onDelete,
}: {
    product: ProductProps;
    onClick: (product: ProductProps) => void;
    onDelete: (product: ProductProps) => void;
}) {
    const [open, setOpen] = useState(false);
    const menuRef = useRef<HTMLDivElement | null>(null);

    function editProd(e: React.MouseEvent<HTMLDivElement | HTMLAnchorElement>) {
        e.stopPropagation();
        onClick(product);
    }

    function toggleMenu(e: React.MouseEvent<HTMLDivElement>) {
        e.stopPropagation();
        setOpen(!open);
    }

    function handleDelete(e: React.MouseEvent<HTMLAnchorElement>) {
        e.stopPropagation();
        onDelete(product);
    }

    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            if (
                menuRef.current &&
                !menuRef.current.contains(event.target as Node)
            ) {
                setOpen(false);
            }
        };
        document.addEventListener("mousedown", handleClickOutside);
        return () =>
            document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    return (
        <div
            onClick={editProd}
            className="bg-white border border-gray-200 dark:border-gray-700 shadow-sm product-card drop-shadow-lg drop-shadow-gray-300 dark:drop-shadow-gray-900"
        >
            <div className="img-container">
                <img
                    src={`${
                        product.image
                            ? `/storage/images/${product.image}`
                            : "/images/no-photo.png"
                    }`}
                    alt="image"
                />
            </div>
            <div className="title-container font-bold line-clamp-1 text-lg text-white">
                <p>{product.nombre.toUpperCase()}</p>
            </div>
            <div className="text-category text-gray-700 line-clamp-1 font-semibold">
                <p>{product.categoria?.nombre}</p>
            </div>
            <div className="text-description text-gray-500 line-clamp-3 text-sm">
                <p>{product.descripcion}</p>
            </div>
            <div
                id="action-button"
                className={`actions ${
                    product.active
                        ? "bg-green-500 drop-shadow-sm dark:drop-shadow-md drop-shadow-green-500"
                        : "bg-gray-500 drop-shadow-sm dark:drop-shadow-md drop-shadow-gray-500"
                }`}
                onClick={toggleMenu}
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    height="24px"
                    viewBox="0 -960 960 960"
                    width="24px"
                    fill="#e3e3e3"
                >
                    <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z" />
                </svg>
            </div>
            <div
                ref={menuRef}
                className={`absolute right-5 top-4 z-10 w-56 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black/5 transition ${
                    open
                        ? "opacity-100 pointer-events-auto"
                        : "opacity-0 pointer-events-none"
                }`}
                role="menu"
            >
                <div className="py-1">
                    <a
                        href="#"
                        className="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                        role="menuitem"
                        onClick={editProd}
                    >
                        Editar
                    </a>
                    <a
                        href="#"
                        className="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                        role="menuitem"
                    >
                        Desactivar
                    </a>
                    <a
                        href="#"
                        className="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700"
                        role="menuitem"
                        onClick={handleDelete}
                    >
                        Eliminar
                    </a>
                </div>
            </div>
        </div>
    );
}
