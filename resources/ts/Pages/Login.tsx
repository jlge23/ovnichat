import Card from "@/components/Card";
import Input from "@/components/Input";
import LayoutGuest from "@/Layouts/LayoutGuest";
import { Head, usePage } from "@inertiajs/react";
import { ChangeEvent, useState } from "react";

export default function Login() {
    const { props } = usePage();

    const [form, setForm] = useState({
        email: "",
        password: "",
    });

    function handleForm(e: ChangeEvent<HTMLInputElement>) {
        const { name, value } = e.target;

        setForm((prev) => ({
            ...prev,
            [name]: value,
        }));
    }

    return (
        <>
            <Head>
                <title>
                    {props.appName
                        ? props.appName + " - Iniciar Sesión"
                        : "Iniciar Sesión"}
                </title>
            </Head>
            <LayoutGuest>
                <div className="h-screen flex justify-center items-center">
                    <div className="w-100 h-100">
                        <Card className="p-5">
                            <Input
                                id="email"
                                name="email"
                                label="Correo Electrónico"
                                value={form.email}
                                onChange={handleForm}
                                placeholder="Introduzca su correo electrónico"
                            />
                            <Input
                                id="password"
                                name="password"
                                label="Contraseña"
                                type="password"
                                value={form.password}
                                onChange={handleForm}
                                placeholder="Introduzca su contraseña"
                            />
                        </Card>
                    </div>
                </div>
            </LayoutGuest>
        </>
    );
}
