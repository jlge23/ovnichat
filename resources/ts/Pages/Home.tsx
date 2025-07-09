import { Head, usePage } from "@inertiajs/react";
import LayoutGuest from "@/Layouts/LayoutGuest";
import { InertiaSharedProps } from "@/types/inertia";

export default function Home() {
    const { appName } = usePage<InertiaSharedProps>().props;

    return (
        <LayoutGuest>
            <Head>
                <title>{appName}</title>
                <meta name="description" content="Your page description" />
            </Head>
            <div>
                <div className="h-screen relative">
                    <div className="absolute top-40 left-40 w-[750px]">
                        <div className="flex gap-2 mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl lg:text-6xl dark:text-white">
                            <h2>Conozca</h2>
                            <h1>{appName}</h1>
                        </div>
                        <p className="text-3xl">
                            ¡Presentamos nuestro nuevo CRM (Customer
                            Relationship Management), la herramienta definitiva
                            para transformar la forma en que tu negocio se
                            relaciona, gestiona y crece con sus clientes!
                        </p>
                    </div>
                </div>
            </div>
        </LayoutGuest>
    );
}
