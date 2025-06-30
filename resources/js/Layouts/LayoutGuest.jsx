import { Link, usePage } from "@inertiajs/react";

export default function LayoutGuest({ children }) {
    const { props } = usePage();
    const currentDate = new Date();

    return (
        <div className="bg-white dark:bg-gray-700 w-screen min-h-screen text-black dark:text-gray-100 flex flex-col">
            <header className="p-2">
                <div className="flex gap-3 items-center place-content-between ">
                    <div className="flex justify-center items-center gap-3">
                        {/* <img
                            src="https://syschatwp.com/syschat/assets/img/chat.png"
                            alt="Logo"
                            style={{ width: 50, height: 50 }}
                        /> */}
                        <h2 className="text-lg font-bold">{props.appName}</h2>
                    </div>
                </div>
            </header>
            <main className="flex-1 p-4">{children}</main>
            <footer>
                <div className="w-full px-5">
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 py-3">
                        <div className="flex justify-center items-center">
                            {/* <img
                                src="https://syschatwp.com/syschat/assets/img/chat.png"
                                alt="Logo"
                                style={{ width: "100px", height: "100px" }}
                            /> */}
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
                                        href="#"
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
                                        Contacto
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="#"
                                        className="text-sm hover:underline"
                                    >
                                        Soporte
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div className="py-3 flex justify-end items-center">
                        <p className="text-sm">
                            {`© ${currentDate.getFullYear()} ${
                                props.appName
                            }. Todos los derechos reservados`}
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
