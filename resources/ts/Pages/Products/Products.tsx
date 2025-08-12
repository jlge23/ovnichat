import { useEffect, useState } from "react";
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
import ProductCard from "./components/ProductCard";
import Swal from "sweetalert2";
import { showToast } from "@/utils/Toast";

type Props = InertiaSharedProps & { productos: null | ProductProps[] };

export default function Products() {
    // const { url } = usePage<InertiaSharedProps>();

    const [showModal, setShowModal] = useState<boolean>(false);
    const [formOptions, setFormOptions] = useState<FromOptions | null>(null);
    const {
        data,
        setData,
        post,
        processing,
        errors,
        clearErrors,
        reset,
        delete: destroy,
        transform,
    } = useForm<ProductForm>({
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
    } = usePage<Props>();

    const [products, setProducts] = useState<ProductProps[]>([]);
    const [page, setPage] = useState(1);
    const [loading, setLoading] = useState(false);
    const [editingProduct, setEditingProduct] = useState<
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

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (processing) return;

        if (editingProduct) {
            transform((data) => ({
                ...data,
                image: data.image instanceof File ? data.image : null,
                _method: "PUT",
            }));

            const prodId = editingProduct.id;

            post(route("productos.update", prodId), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: (res: { props: Props }) => {
                    const updatedProd: ProductProps =
                        res.props.productos?.data.find(
                            (v: ProductProps) => v.id === prodId
                        );

                    setProducts((prev) =>
                        prev.map((p) => {
                            if (p.id !== editingProduct.id) return p;

                            return {
                                ...p,
                                ...updatedProd,
                            };
                        })
                    );

                    cancelForm();

                    showToast(
                        "success",
                        `El producto ${updatedProd.nombre} ha sido actualizado.`
                    );
                },
                onError: (err) => {
                    showToast(
                        "error",
                        `Error al actualizar el producto ${editingProduct.nombre}.`
                    );
                    console.error(err);
                },
            });
            return;
        }

        post(route("productos.store"), {
            onSuccess: () => {
                showToast(
                    "success",
                    `El producto ${data.nombre} ha sido registrado correctamente.`
                );
                reset();
                toggleModal();
            },
        });
    };

    function cancelForm() {
        setEditingProduct(undefined);
        reset();
        clearErrors();
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

    function deleteElement(product: ProductProps) {
        Swal.fire({
            title: `¿Desea Eliminar ${product.nombre}?`,
            text: "Éste proceso no puede ser revertido.",
            showCancelButton: true,
            confirmButtonText: "Eliminar",
            cancelButtonText: `Cancelar`,
            confirmButtonColor: "#d33",
            icon: "warning",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                destroy(route("productos.destroy", product.id), {
                    preserveScroll: true,
                    onSuccess: () => {
                        setProducts((prev) =>
                            prev.filter((p) => p.id !== product.id)
                        );
                        console.log("Producto eliminado");
                    },
                    onError: (err) => console.error(err),
                });
            } else if (result.isDenied) {
                Swal.fire("Changes are not saved", "", "info");
            }
        });
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
                            <ProductCard
                                onClick={openProduct}
                                product={item}
                                onDelete={deleteElement}
                            />
                        </div>
                    ))}
                </div>
                {loading && <p className="text-center mt-4">Cargando más...</p>}
            </div>
            <FAB onClick={toggleModal} />
            <Modal show={showModal} toggleModal={toggleModal}>
                <Form
                    submit={handleSubmit}
                    data={data}
                    formOptions={formOptions}
                    setData={setData}
                    errors={errors}
                    processing={processing}
                    cancelForm={cancelForm}
                    productId={editingProduct?.id}
                />
            </Modal>
        </LayoutAuth>
    );
}
