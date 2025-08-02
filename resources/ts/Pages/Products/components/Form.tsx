import { useForm } from "@inertiajs/react";
import { ProductForm } from "@/api/products";
import { Button } from "@/components/Button";
import Input from "@/components/Input";
import SelectField, { SelectOption } from "@/components/SelectField";
import Spinner from "@/components/Spinner";
import Textarea from "@/components/Textarea";
import ToggleSwitch from "@/components/ToggleSwitch";

type FormHook = ReturnType<typeof useForm<ProductForm>>;

type Form = {
    submit: (e: React.FormEvent) => void;
    data: FormHook["data"];
    formOptions?: FromOptions | null;
    setData: FormHook["setData"];
    errors: FormHook["errors"];
    processing: boolean;
    cancelForm: () => void;
    productId?: number;
};

export type FromOptions = {
    categorias: SelectOption[];
    embalajes: SelectOption[];
    marcas: SelectOption[];
    proveedores: SelectOption[];
    unidadesMedidas: SelectOption[];
};

export default function Form({
    submit,
    data,
    formOptions,
    setData,
    errors,
    processing,
    cancelForm,
    productId,
}: Form) {
    return (
        <div>
            <form onSubmit={submit} encType="multipart/form-data">
                <h2 className="text-lg font-semibold mb-4">
                    {productId ? "Modificar " : "Registrar "}
                    Producto
                </h2>
                {typeof data.image === "string" && data.image ? (
                    <img
                        src={`/storage/images/${data.image}`}
                        alt="photo"
                        className="size-80"
                    />
                ) : null}
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
                    onChange={(e) =>
                        setData("nombre", e.target.value.toUpperCase())
                    }
                    errorMessage={errors.nombre}
                    errorActive={!!errors.nombre}
                    required
                />
                {/* Descripción */}
                <Textarea
                    label="Descripción"
                    name="descripcion"
                    value={data.descripcion}
                    onChange={(e) => setData("descripcion", e.target.value)}
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
                                setData("marca_id", parseInt(e.target.value))
                            }
                            showDefaultField={false}
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
                                setData(
                                    "categoria_id",
                                    parseInt(e.target.value)
                                )
                            }
                            showDefaultField={false}
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
                                setData(
                                    "proveedor_id",
                                    parseInt(e.target.value)
                                )
                            }
                            showDefaultField={false}
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
                                    parseInt(e.target.value)
                                )
                            }
                            showDefaultField={false}
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
                                setData("embalaje_id", parseInt(e.target.value))
                            }
                            showDefaultField={false}
                        />
                    </>
                ) : (
                    <div className="flex justify-center">
                        <div className="size-20">
                            <Spinner />
                        </div>
                    </div>
                )}
                <Input
                    darkMode={false}
                    type="number"
                    name={"stock_actual"}
                    label={"Stock actual"}
                    value={data.stock_actual}
                    onChange={(e) =>
                        setData("stock_actual", parseInt(e.target.value))
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
                        setData(
                            "unidades_por_embalaje",
                            parseInt(e.target.value)
                        )
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
                        setData("precio_detal", parseInt(e.target.value))
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
                        setData("precio_embalaje", parseInt(e.target.value))
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
                        {productId ? "Modificar" : "Aceptar"}
                    </Button>
                    <Button type="button" variant="light" onClick={cancelForm}>
                        Cancelar
                    </Button>
                </div>
            </form>
        </div>
    );
}
