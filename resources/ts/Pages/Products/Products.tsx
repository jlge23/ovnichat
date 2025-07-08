import { useEffect, useState } from "react";
import LayoutAuth from "@/Layouts/LayoutAuth";
import { Head, usePage } from "@inertiajs/react";
import "./Products.css";
import { InertiaSharedProps } from "@/types/inertia";

type ProductProps = {
    id: string;
    nombre: string;
    codigo_sku: string;
    descripcion: string;
    categoria: {
        nombre: string;
    };
    active: boolean;
};

const Product = ({ product }: { product: ProductProps }) => (
    <div className="bg-white border border-gray-200 dark:border-gray-700 shadow-sm product-card drop-shadow-lg drop-shadow-gray-300 dark:drop-shadow-gray-900">
        <div className="img-container"></div>
        <div className="title-container font-bold line-clamp-1 text-lg text-white">
            <p>{product.nombre}</p>
        </div>
        <div className="text-category text-gray-700 line-clamp-1 font-semibold">
            <p>{product.categoria.nombre}</p>
        </div>
        <div className="text-description text-gray-500 line-clamp-3 text-sm">
            <p>{product.descripcion}</p>
        </div>
        <div
            className={`actions ${
                product.active
                    ? "bg-green-500 drop-shadow-sm dark:drop-shadow-md drop-shadow-green-500"
                    : "bg-gray-500 drop-shadow-sm dark:drop-shadow-md drop-shadow-gray-500"
            }
        }`}
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
    </div>
);

export default function Products() {
    // const { url } = usePage<InertiaSharedProps>();

    const {
        props: { appName, productos },
    } = usePage<InertiaSharedProps & { productos: null | ProductProps[] }>();

    const [products, setProducts] = useState<ProductProps[]>([]);
    const [page, setPage] = useState(1);
    const [loading, setLoading] = useState(false);

    const handleScroll = () => {
        if (
            window.innerHeight + window.scrollY >=
            document.body.offsetHeight - 100
        ) {
            loadMore();
        }
    };

    useEffect(() => {
        // window.addEventListener("scroll", handleScroll);
        // return () => window.removeEventListener("scroll", handleScroll);
        fetchProducts();
    }, []);

    // useEffect(() => {
    //     fetchProducts(page);
    // }, [page]);

    function fetchProducts() {
        // async function fetchProducts(page: number) {
        if (loading) return;

        setLoading(true);
        try {
            if (productos) {
                setProducts(productos);
            }
        } catch (err) {
            console.error(err);
        } finally {
            setLoading(false);
        }
    }

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
            <div>
                <h1 className="text-2xl font-bold mb-4 text-gray-700 dark:text-white">
                    Productos
                </h1>

                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 w-full">
                    {products.map((item, key) => (
                        <div key={key}>
                            <Product product={item} />
                        </div>
                    ))}
                </div>
                {loading && <p className="text-center mt-4">Cargando más...</p>}
            </div>
        </LayoutAuth>
    );
}
