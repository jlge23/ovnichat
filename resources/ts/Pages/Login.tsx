import { Button } from "@/components/Button";
import Card from "@/components/Card";
import Input from "@/components/Input";
import LayoutGuest from "@/Layouts/LayoutGuest";
import { Head, usePage, router } from "@inertiajs/react";
import { ChangeEvent, FormEvent, useState } from "react";

export default function Login() {
    const { errors, appName } = usePage().props;

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

    function handleSubmit(e: FormEvent<HTMLFormElement>) {
        e.preventDefault();
        router.post("/login", form);
    }

    return (
        <>
            <Head>
                <title>
                    {appName ? appName + " - Iniciar Sesión" : "Iniciar Sesión"}
                </title>
            </Head>
            <LayoutGuest>
                <div className="h-screen flex justify-center items-center">
                    <div className="w-100 h-100">
                        <Card className="p-5">
                            <div className="flex items-center w-full h-full">
                                <form
                                    onSubmit={handleSubmit}
                                    className="w-full"
                                >
                                    <Input
                                        id="email"
                                        name="email"
                                        label="Correo Electrónico"
                                        value={form.email}
                                        onChange={handleForm}
                                        placeholder="Introduzca su correo electrónico"
                                        errorActive={errors.email && true}
                                        errorMessage={errors.email}
                                    />
                                    <Input
                                        id="password"
                                        name="password"
                                        label="Contraseña"
                                        type="password"
                                        value={form.password}
                                        onChange={handleForm}
                                        placeholder={"Introduzca su contraseña"}
                                        errorActive={errors.password && true}
                                        errorMessage={errors.password}
                                    />
                                    <Button variant="green" type="submit">
                                        Iniciar Sesión
                                    </Button>
                                </form>
                            </div>
                        </Card>
                    </div>
                </div>
            </LayoutGuest>
        </>
    );
}
