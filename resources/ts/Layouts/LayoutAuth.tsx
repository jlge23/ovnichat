import Card from "@/components/Card";
import Logo from "@/components/Logo";
import { InertiaSharedProps } from "@/types/inertia";
import { Link, router, usePage } from "@inertiajs/react";
import { ReactNode } from "react";

type LayoutAuthProps = { children: ReactNode };

export default function LayoutAuth({ children }: LayoutAuthProps) {
    const {
        props: {
            appName,
            auth: { user },
        },
        url,
    } = usePage<InertiaSharedProps>();
    const currentDate = new Date();

    function closeSession() {
        router.post(
            "logout",
            {},
            {
                replace: true,
            }
        );
    }

    function urlIsActive(path: string) {
        return url === path;
    }

    return (
        <div className="bg-white dark:bg-gray-700 w-screen min-h-screen text-black dark:text-gray-100 flex flex-col">
            <header className="p-2">
                <div className="flex gap-3 items-center place-content-between ">
                    <Link
                        href="/dashboard"
                        className="flex justify-center items-center gap-3"
                    >
                        <Logo className="w-15" />
                        <h2 className="text-lg font-bold">{appName}</h2>
                    </Link>
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
            <main className="flex-1 p-4 flex">
                <div className="fixed">
                    <Card className="p-5 rounded-xl">
                        <ul className="list-none">
                            <li>
                                <Link
                                    href="/dashboard"
                                    className={`flex gap-2 mb-2 ${
                                        urlIsActive("/dashboard")
                                            ? "font-bold border-b-1"
                                            : ""
                                    }`}
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        height="30px"
                                        viewBox="0 -960 960 960"
                                        width="30px"
                                        fill="#e3e3e3"
                                    >
                                        <path d="M600-160v-280h280v280H600ZM440-520v-280h440v280H440ZM80-160v-280h440v280H80Zm0-360v-280h280v280H80Zm440-80h280v-120H520v120ZM160-240h280v-120H160v120Zm520 0h120v-120H680v120ZM160-600h120v-120H160v120Zm360 0Zm-80 240Zm240 0ZM280-600Z" />
                                    </svg>
                                    <p>Inicio</p>
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="#"
                                    className={`flex gap-2 mb-2 ${
                                        urlIsActive("#")
                                            ? "font-bold border-b-1"
                                            : ""
                                    }`}
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        height="30px"
                                        viewBox="0 -960 960 960"
                                        width="30px"
                                        fill="#e3e3e3"
                                    >
                                        <path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
                                    </svg>
                                    <p>Productos</p>
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="#"
                                    className={`flex gap-2 mb-2 ${
                                        urlIsActive("#")
                                            ? "font-bold border-b-1"
                                            : ""
                                    }`}
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        height="30px"
                                        viewBox="0 -960 960 960"
                                        width="30px"
                                        fill="#e3e3e3"
                                    >
                                        <path d="M440-183v-274L200-596v274l240 139Zm80 0 240-139v-274L520-457v274Zm-80 92L160-252q-19-11-29.5-29T120-321v-318q0-22 10.5-40t29.5-29l280-161q19-11 40-11t40 11l280 161q19 11 29.5 29t10.5 40v318q0 22-10.5 40T800-252L520-91q-19 11-40 11t-40-11Zm200-528 77-44-237-137-78 45 238 136Zm-160 93 78-45-237-137-78 45 237 137Z" />
                                    </svg>
                                    <p>Combos</p>
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="#"
                                    className={`flex gap-2 mb-2 ${
                                        urlIsActive("#")
                                            ? "font-bold border-b-1"
                                            : ""
                                    }`}
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        height="30px"
                                        viewBox="0 -960 960 960"
                                        width="30px"
                                        fill="#e3e3e3"
                                    >
                                        <path d="M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                                    </svg>
                                    <p>Chats</p>
                                </Link>
                            </li>
                        </ul>
                    </Card>
                </div>
                <div className="w-full">{children}</div>
            </main>
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
