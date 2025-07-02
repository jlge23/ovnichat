import { useEffect, useState } from "react";
import LayoutAuth from "@/Layouts/LayoutAuth";
import { Head, usePage } from "@inertiajs/react";
import Card from "@/components/Card";

const Product = () => (
    <Card className="relative p-5">
        <h2>Producto</h2>
        <div>
            <div>N°</div>
            <div>Codigo SKU</div>
            <div>Nombre y Descripción</div>
            <div>U/M</div>
            <div>$: detal / embalaje</div>
            <div>Categoría</div>
            <div>Stock actual</div>
            <div>Estatus</div>
        </div>
        <div className="absolute right-5 top-5">
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
    </Card>
);

export default function Products() {
    const { appName } = usePage().props;

    const [products, setProducts] = useState<number[]>([]);
    const [page, setPage] = useState(1);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            if (
                window.innerHeight + window.scrollY >=
                document.body.offsetHeight - 100
            ) {
                loadMore();
            }
        };

        window.addEventListener("scroll", handleScroll);
        return () => window.removeEventListener("scroll", handleScroll);
    }, []);

    useEffect(() => {
        // fetchProducts(page);
    }, [page]);

    // async function fetchProducts(page: number) {
    //     setLoading(true);

    //     // Simulación de carga (puedes cambiar por Axios o Inertia visit)
    //     // await new Promise((r) => setTimeout(r, 1000));
    //     // const newItems = Array.from(
    //     //     { length: 10 },
    //     //     (_, i) => i + (page - 1) * 10
    //     // );
    //     setProducts((prev) => prev);

    //     setLoading(false);
    // }

    const loadMore = () => {
        if (!loading) {
            setPage((prev) => prev + 1);
        }
    };

    return (
        <LayoutAuth>
            <Head>
                <title>
                    {appName ? appName + " - Productos" : "Productos"}
                </title>
            </Head>
            <h1 className="text-2xl font-bold mb-4">Productos</h1>
            <Product />
            {products.map((item) => (
                <div key={item} className="p-4 border-b border-gray-200">
                    Producto #{item}
                </div>
            ))}
            {loading && <p className="text-center mt-4">Cargando más...</p>}
        </LayoutAuth>
    );
}
