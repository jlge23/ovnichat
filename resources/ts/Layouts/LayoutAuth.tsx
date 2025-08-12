import Card from "@/components/Card";
import Logo from "@/components/Logo";
import { InertiaSharedProps } from "@/types/inertia";
import { Link, router, usePage } from "@inertiajs/react";
import { ReactNode, useState } from "react";
import { route } from "ziggy-js";

type LayoutAuthProps = { children: ReactNode };

const Menu = ({
    showMenu,
    setShowMenu,
}: {
    showMenu: boolean;
    setShowMenu: () => void;
}) => {
    const { url } = usePage<InertiaSharedProps>();

    const urls: { href: string; description: string; icon: string }[] = [
        {
            href: route("dashboard"),
            description: "Inicio",
            icon: "M600-160v-280h280v280H600ZM440-520v-280h440v280H440ZM80-160v-280h440v280H80Zm0-360v-280h280v280H80Zm440-80h280v-120H520v120ZM160-240h280v-120H160v120Zm520 0h120v-120H680v120ZM160-600h120v-120H160v120Zm360 0Zm-80 240Zm240 0ZM280-600Z",
        },
        {
            href: route("productos.index"),
            description: "Productos",
            icon: "M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z",
        },
        {
            href: route("flujos.index"),
            description: "Constructor de Flujos",
            icon: "M600-160v-80H440v-200h-80v80H80v-240h280v80h80v-200h160v-80h280v240H600v-80h-80v320h80v-80h280v240H600Zm80-80h120v-80H680v80ZM160-440h120v-80H160v80Zm520-200h120v-80H680v80Zm0 400v-80 80ZM280-440v-80 80Zm400-200v-80 80Z",
        },
        {
            href: "#",
            description: "Combos",
            icon: "M440-183v-274L200-596v274l240 139Zm80 0 240-139v-274L520-457v274Zm-80 92L160-252q-19-11-29.5-29T120-321v-318q0-22 10.5-40t29.5-29l280-161q19-11 40-11t40 11l280 161q19 11 29.5 29t10.5 40v318q0 22-10.5 40T800-252L520-91q-19 11-40 11t-40-11Zm200-528 77-44-237-137-78 45 238 136Zm-160 93 78-45-237-137-78 45 237 137Z",
        },
        {
            href: "#",
            description: "Chats",
            icon: "M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z",
        },
    ];

    function urlIsActive(path: string) {
        if (path === "#") return false;

        const currentPath = new URL(path).pathname;
        return url === currentPath;
    }

    return (
        <div
            className={`fixed p-2 transition-all ${
                showMenu ? "w-[190px]" : "w-[90px]"
            }`}
        >
            <Card className="p-3 rounded-xl">
                <ul className="list-none">
                    <li>
                        <div className="mb-2" onClick={setShowMenu}>
                            <Logo className="w-12" />
                        </div>
                    </li>
                    {urls.map((val, key) => (
                        <li key={key}>
                            <Link
                                href={val.href}
                                className={`flex gap-2 mb-2 ml-2 hover:text-gray-600 dark:hover:text-white ${
                                    urlIsActive(val.href)
                                        ? "font-bold dark:text-white text-gray-600"
                                        : "text-gray-400"
                                }`}
                            >
                                <svg
                                    className="size-8 min-w-[32px] fill-current transition-all"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 -960 960 960"
                                >
                                    <path d={val.icon} />
                                </svg>
                                <p
                                    className={`transition-all duration-300
                                    ${
                                        showMenu
                                            ? "opacity-100 translate-x-0"
                                            : "opacity-0 -translate-x-10 text-nowrap"
                                    }
                                `}
                                >
                                    {val.description}
                                </p>
                            </Link>
                        </li>
                    ))}
                </ul>
            </Card>
        </div>
    );
};

export default function LayoutAuth({ children }: LayoutAuthProps) {
    const { appName } = usePage<InertiaSharedProps>().props;
    const currentDate = new Date();

    const [showMenu, setShowMenu] = useState<boolean>(false);

    function closeSession() {
        router.post(
            "logout",
            {},
            {
                replace: true,
            }
        );
    }

    return (
        <div className="bg-white dark:bg-gray-700 w-screen min-h-screen text-black dark:text-gray-100 flex flex-col">
            <div>
                <Menu
                    showMenu={showMenu}
                    setShowMenu={() => setShowMenu(!showMenu)}
                />
            </div>
            <div
                className={`flex-1 transition-all duration-300 ${
                    showMenu ? "ml-[190px]" : "ml-[90px]"
                }`}
            >
                <header className="px-2 py-5">
                    <div className="flex gap-3 items-center place-content-between ">
                        <h2 className="text-lg font-bold text-gray-700 dark:text-white">
                            {appName}
                        </h2>
                        <div>
                            <div
                                className="hover:cursor-pointer"
                                onClick={closeSession}
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    height="24px"
                                    viewBox="0 -960 960 960"
                                    width="24px"
                                    fill="#e3e3e3"
                                >
                                    <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </header>
                <main className="flex-1 p-4 flex">{children}</main>
            </div>
            <footer className="bg-gray-300 dark:bg-gray-800">
                <div className="w-full px-5">
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 py-3">
                        <div className="flex justify-center items-center">
                            <Logo className="w-30" />
                        </div>
                        <div>
                            <h5 className="text-lg font-bold">About</h5>
                            <p className="text-sm">
                                Aprende más acerca de nuestra misión y valores.
                            </p>
                        </div>
                        <div>
                            <h5 className="text-lg font-bold">
                                Normativas y Políticas
                            </h5>
                            <ul className="list-none">
                                <li>
                                    <Link
                                        href="#"
                                        className="text-sm hover:underline"
                                    >
                                        Términos de Servicio
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="#"
                                        className="text-sm hover:underline"
                                    >
                                        Protección de los Datos
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="#"
                                        className="text-sm hover:underline"
                                    >
                                        Cookies
                                    </Link>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h5 className="text-lg font-bold">Menú</h5>
                            <ul className="list-none">
                                <li>
                                    <Link
                                        href="dashboard"
                                        className="text-sm hover:underline"
                                    >
                                        Inicio
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="#"
                                        className="text-sm hover:underline"
                                    >
                                        Productos
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="#"
                                        className="text-sm hover:underline"
                                    >
                                        Combos
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div className="py-3 flex justify-end items-center">
                        <p className="text-sm">
                            {`© ${currentDate.getFullYear()} ${appName}. Todos los derechos reservados`}
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
