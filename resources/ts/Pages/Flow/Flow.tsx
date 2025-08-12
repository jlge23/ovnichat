import LayoutAuth from "@/Layouts/LayoutAuth";
import { Head, usePage } from "@inertiajs/react";

export default function index() {
    const {
        props: { appName },
    } = usePage();

    return (
        <LayoutAuth>
            <Head>
                <title>
                    {appName
                        ? appName + " - Constructor de Flujos"
                        : "Constructor de Flujos"}
                </title>
            </Head>
            index
        </LayoutAuth>
    );
}
