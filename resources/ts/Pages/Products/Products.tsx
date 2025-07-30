import { useEffect, useRef, useState } from "react";
import LayoutAuth from "@/Layouts/LayoutAuth";
import { Head, usePage } from "@inertiajs/react";
import "./Products.css";
import { InertiaSharedProps } from "@/types/inertia";
import FAB from "@/components/FAB";
import Modal from "@/components/Modal";
import { useForm } from "@inertiajs/react";
import { route } from "ziggy-js";
import {
    fetchProductSelectOptions,
    ProductForm,
    ProductProps,
} from "@/api/products";
import { formatToSelect } from "@/utils/select";
import Form, { FromOptions } from "./components/Form";

const Product = ({
    product,
    onClick,
}: {
    product: ProductProps;
    onClick: () => void;
}) => {
    const [open, setOpen] = useState(false);
    const menuRef = useRef<HTMLDivElement | null>(null);

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
            onClick={onClick}
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
                <p>{product.nombre}</p>
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
                onClick={() => setOpen(!open)}
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
                    >
                        Eliminar
                    </a>
                </div>
            </div>
        </div>
    );
};

export default function Products() {
    // const { url } = usePage<InertiaSharedProps>();

    const [showModal, setShowModal] = useState<boolean>(false);
    const [formOptions, setFormOptions] = useState<FromOptions | null>(null);
    const { data, setData, post, processing, errors, reset, put } =
        useForm<ProductForm>({
            gtin: "",
            nombre: "",
            descripcion: "",
            marca_id: 1,
            categoria_id: 1,
            proveedor_id: 1,
            unidad_medida_id: 1,
            stock_actual: 0,
            embalaje_id: 1,
            unidades_por_embalaje: 0,
            precio_detal: 0,
            precio_embalaje: 0,
            costo_detal: 0,
            active: true,
            image: null,
        });

    const {
        props: { appName, productos },
    } = usePage<InertiaSharedProps & { productos: null | ProductProps[] }>();

    const [products, setProducts] = useState<ProductProps[]>([]);
    const [page, setPage] = useState(1);
    const [loading, setLoading] = useState(false);
    const [activeProduct, setEditingProduct] = useState<
        ProductProps | undefined
    >();

    const handleScroll = () => {
        if (loading) return;

        if (
            window.innerHeight + window.scrollY >=
            document.body.offsetHeight - 100
        ) {
            setPage((prev) => prev + 1);
        }
    };

    useEffect(() => {
        setProducts(productos.data);

        window.addEventListener("scroll", handleScroll);
        return () => window.removeEventListener("scroll", handleScroll);
    }, []);

    useEffect(() => {
        if (page > 1) fetchProducts();
    }, [page]);

    function fetchProducts() {
        if (loading) return;
        setLoading(true);

        try {
            // Construcción estricta de la URL solo si hay página
            const url = `/productos?page=${page}`;

            fetch(url)
                .then((res) => res.json())
                .then((json: { productos: { data: ProductProps[] } }) => {
                    setProducts((prev) => [...prev, ...json.productos.data]);
                })
                .catch((err) => console.error(err))
                .finally(() => setLoading(false));
        } catch (err) {
            console.error(err);
        } finally {
            setLoading(false);
        }
    }

    function toggleModal() {
        if (!showModal && !formOptions) {
            getProductSelectOptions();
        }
        setShowModal(!showModal);
    }

    const submit = (e: React.FormEvent) => {
        e.preventDefault();

        if (processing) return;

        if (activeProduct) {
            put(route("productos.update", activeProduct), {
                onSuccess: () => {
                    cancelForm();
                },
            });
            return;
        }

        post(route("productos.store"), {
            onSuccess: () => {
                reset();
                toggleModal();
            },
        });
    };

    function cancelForm() {
        setEditingProduct(undefined);
        reset();
        toggleModal();
    }

    function openProduct(product: ProductProps) {
        setData(() => ({
            gtin: product.gtin,
            nombre: product.nombre,
            descripcion: product.descripcion,
            marca_id: product.marca_id,
            categoria_id: product.categoria_id,
            proveedor_id: product.proveedor_id,
            unidad_medida_id: product.unidad_medida_id,
            stock_actual: product.stock_actual,
            embalaje_id: product.embalaje_id,
            unidades_por_embalaje: product.unidades_por_embalaje,
            precio_detal: product.precio_detal,
            precio_embalaje: product.precio_embalaje,
            costo_detal: product.costo_detal,
            active: product.active,
            image: product.image,
        }));
        toggleModal();
        setEditingProduct(product);
    }

    async function getProductSelectOptions() {
        try {
            const res = await fetchProductSelectOptions();

            const formated = {
                categorias: formatToSelect(res.categorias, "id", "nombre"),
                embalajes: formatToSelect(res.embalajes, "id", "nombre"),
                marcas: formatToSelect(res.marcas, "id", "nombre"),
                proveedores: formatToSelect(res.proveedores, "id", "nombre"),
                unidadesMedidas: formatToSelect(
                    res.unidadesMedidas,
                    "id",
                    "nombre"
                ),
            };

            setFormOptions(formated);
        } catch (err) {
            console.error("Error al cargar datos del formulario:", err);
        }
    }

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
                            <Product
                                onClick={() => openProduct(item)}
                                product={item}
                            />
                        </div>
                    ))}
                </div>
                {loading && <p className="text-center mt-4">Cargando más...</p>}
            </div>
            <FAB onClick={toggleModal} />
            <Modal show={showModal} toggleModal={toggleModal}>
                <Modal show={showModal} toggleModal={toggleModal}>
                    <Form
                        submit={submit}
                        data={data}
                        formOptions={formOptions}
                        setData={setData}
                        errors={errors}
                        processing={processing}
                        cancelForm={cancelForm}
                        productId={activeProduct?.id}
                    />
                </Modal>
            </Modal>
        </LayoutAuth>
    );
}
