import { useEffect, useRef, useState } from "react";
import LayoutAuth from "@/Layouts/LayoutAuth";
import { Head, usePage } from "@inertiajs/react";
import "./Products.css";
import { InertiaSharedProps } from "@/types/inertia";
import FAB from "@/components/FAB";
import Modal from "@/components/Modal";
import Input from "@/components/Input";
import { useForm } from "@inertiajs/react";
import { route } from "ziggy-js";
import SelectField, { SelectOption } from "@/components/SelectField";
import { Button } from "@/components/Button";
import Textarea from "@/components/Textarea";
import Spinner from "@/components/Spinner";
import { fetchProductSelectOptions } from "@/api/products";
import { formatToSelect } from "@/utils/select";
import ToggleSwitch from "@/components/ToggleSwitch";

type ProductProps = {
    id: string;
    nombre: string;
    codigo_sku: string;
    descripcion: string;
    categoria: {
        nombre: string;
    };
    active: boolean;
    image: string;
};

const Product = ({ product }: { product: ProductProps }) => {
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
        <div className="bg-white border border-gray-200 dark:border-gray-700 shadow-sm product-card drop-shadow-lg drop-shadow-gray-300 dark:drop-shadow-gray-900">
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
                <p>#{product.id + " " + product.nombre}</p>
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

type ProductForm = {
    gtin: string;
    nombre: string;
    descripcion: string;
    marca_id: string;
    categoria_id: string;
    proveedor_id: string;
    unidad_medida_id: string;
    stock_actual: string;
    embalaje_id: string;
    unidades_por_embalaje: string;
    precio_detal: string;
    precio_embalaje: string;
    costo_detal: string;
    active: boolean;
    image: File | null;
};

type FromOptions = {
    categorias: SelectOption[];
    embalajes: SelectOption[];
    marcas: SelectOption[];
    proveedores: SelectOption[];
    unidadesMedidas: SelectOption[];
};

export default function Products() {
    // const { url } = usePage<InertiaSharedProps>();

    const [showModal, setShowModal] = useState<boolean>(false);
    const [formOptions, setFormOptions] = useState<FromOptions | null>(null);
    const { data, setData, post, processing, errors, reset } =
        useForm<ProductForm>({
            gtin: "",
            nombre: "",
            descripcion: "",
            marca_id: "",
            categoria_id: "",
            proveedor_id: "",
            unidad_medida_id: "",
            stock_actual: "",
            embalaje_id: "",
            unidades_por_embalaje: "",
            precio_detal: "",
            precio_embalaje: "",
            costo_detal: "",
            active: true,
            image: null,
        });

    const {
        props: { appName, productos },
    } = usePage<InertiaSharedProps & { productos: null | ProductProps[] }>();

    const [products, setProducts] = useState<ProductProps[]>([]);
    const [page, setPage] = useState(1);
    const [loading, setLoading] = useState(false);

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

        post(route("productos.store"), {
            onSuccess: () => {
                reset();
                toggleModal();
            },
        });
    };

    function cancelForm() {
        reset();
        toggleModal();
    }

    async function getProductSelectOptions() {
        try {
            const res = await fetchProductSelectOptions();

            const formated = {
                categorias: formatToSelect(res.categorias, "id", "nombre"),
                embalajes: formatToSelect(res.embalajes, "id", "tipo_embalaje"),
                marcas: formatToSelect(res.marcas, "id", "marca"),
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
                            <Product product={item} />
                        </div>
                    ))}
                </div>
                {loading && <p className="text-center mt-4">Cargando más...</p>}
            </div>
            <FAB onClick={toggleModal} />
            <Modal show={showModal} toggleModal={toggleModal}>
                <Modal show={showModal} toggleModal={toggleModal}>
                    <form onSubmit={submit}>
                        <h2 className="text-lg font-semibold mb-4">
                            Registrar Producto
                        </h2>

                        <Input
                            darkMode={false}
                            label="Imagen"
                            name="image"
                            type="file"
                            onChange={(e) =>
                                setData("image", e.target.files?.[0] || null)
                            }
                            errorMessage={errors.image}
                            errorActive={!!errors.image}
                        />

                        <Input
                            darkMode={false}
                            label="Código GTIN"
                            name="gtin"
                            value={data.gtin}
                            onChange={(e) => setData("gtin", e.target.value)}
                            errorMessage={errors.gtin}
                            errorActive={!!errors.gtin}
                        />

                        <Input
                            darkMode={false}
                            label="Nombre del producto"
                            name="nombre"
                            value={data.nombre}
                            onChange={(e) => setData("nombre", e.target.value)}
                            errorMessage={errors.nombre}
                            errorActive={!!errors.nombre}
                            required
                        />

                        {/* Descripción */}
                        <Textarea
                            label="Descripción"
                            name="descripcion"
                            value={data.descripcion}
                            onChange={(e) =>
                                setData("descripcion", e.target.value)
                            }
                            errorMessage={errors.descripcion}
                            errorActive={!!errors.descripcion}
                            darkMode={false}
                        />

                        {formOptions ? (
                            <>
                                <SelectField
                                    label="Marca"
                                    name="marca_id"
                                    errorMessage={errors.marca_id}
                                    errorActive={!!errors.marca_id}
                                    options={formOptions.marcas}
                                    darkMode={false}
                                    value={data.marca_id}
                                    onChange={(e) =>
                                        setData("marca_id", e.target.value)
                                    }
                                />

                                <SelectField
                                    label="Categoría"
                                    name="categoria_id"
                                    errorMessage={errors.categoria_id}
                                    errorActive={!!errors.categoria_id}
                                    options={formOptions?.categorias}
                                    darkMode={false}
                                    value={data.categoria_id}
                                    onChange={(e) =>
                                        setData("categoria_id", e.target.value)
                                    }
                                />

                                <SelectField
                                    label="Proveedor"
                                    name="proveedor_id"
                                    errorMessage={errors.proveedor_id}
                                    errorActive={!!errors.proveedor_id}
                                    options={formOptions.proveedores}
                                    darkMode={false}
                                    value={data.proveedor_id}
                                    onChange={(e) =>
                                        setData("proveedor_id", e.target.value)
                                    }
                                />

                                <SelectField
                                    label="Unidad de medida"
                                    name="unidad_medida_id"
                                    errorMessage={errors.unidad_medida_id}
                                    errorActive={!!errors.unidad_medida_id}
                                    options={formOptions.unidadesMedidas}
                                    darkMode={false}
                                    value={data.unidad_medida_id}
                                    onChange={(e) =>
                                        setData(
                                            "unidad_medida_id",
                                            e.target.value
                                        )
                                    }
                                />

                                <SelectField
                                    label="Embalaje"
                                    name="embalaje_id"
                                    errorMessage={errors.embalaje_id}
                                    errorActive={!!errors.embalaje_id}
                                    options={formOptions.embalajes}
                                    darkMode={false}
                                    value={data.embalaje_id}
                                    onChange={(e) =>
                                        setData("embalaje_id", e.target.value)
                                    }
                                />
                            </>
                        ) : (
                            <Spinner />
                        )}

                        <Input
                            darkMode={false}
                            type="number"
                            name={"stock_actual"}
                            label={"Stock actual"}
                            value={data.stock_actual}
                            onChange={(e) =>
                                setData("stock_actual", e.target.value)
                            }
                            errorMessage={errors.stock_actual}
                            errorActive={!!errors.stock_actual}
                        />

                        <Input
                            darkMode={false}
                            type="number"
                            name={"unidades_por_embalaje"}
                            label={"Unidades por embalaje"}
                            value={data.unidades_por_embalaje}
                            onChange={(e) =>
                                setData("unidades_por_embalaje", e.target.value)
                            }
                            errorMessage={errors.unidades_por_embalaje}
                            errorActive={!!errors.unidades_por_embalaje}
                        />

                        <Input
                            darkMode={false}
                            type="number"
                            name={"precio_detal"}
                            label={"Precio por unidad"}
                            value={data.precio_detal}
                            onChange={(e) =>
                                setData("precio_detal", e.target.value)
                            }
                            errorMessage={errors.precio_detal}
                            errorActive={!!errors.precio_detal}
                        />

                        <Input
                            darkMode={false}
                            type="number"
                            name={"precio_embalaje"}
                            label={"Precio por embalaje"}
                            value={data.precio_embalaje}
                            onChange={(e) =>
                                setData("precio_embalaje", e.target.value)
                            }
                            errorMessage={errors.precio_embalaje}
                            errorActive={!!errors.precio_embalaje}
                        />

                        <div>
                            <ToggleSwitch
                                name="active"
                                checked={data.active}
                                onChange={(val) => setData("active", val)}
                                label="Producto activo"
                                darkMode={false}
                            />
                        </div>

                        <div className="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <Button
                                type="submit"
                                variant="purple"
                                disabled={processing}
                            >
                                Aceptar
                            </Button>
                            <Button
                                type="button"
                                variant="light"
                                onClick={cancelForm}
                            >
                                Cancelar
                            </Button>
                        </div>
                    </form>
                </Modal>
            </Modal>
        </LayoutAuth>
    );
}
