import { useRef } from "react";
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
    const fileInputRef = useRef<HTMLInputElement>(null);

    function cancel() {
        if (fileInputRef.current) {
            fileInputRef.current.value = "";
        }
        cancelForm();
    }

    return (
        <div>
            <form onSubmit={submit} encType="multipart/form-data">
                <h2 className="text-lg font-semibold mb-4">
                    {productId ? "Modificar " : "Registrar "}
                    Producto
                </h2>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div className="md:col-span-2 lg:col-span-1">
                        <div className="flex justify-center">
                            <label
                                htmlFor="image"
                                className="cursor-pointer relative"
                            >
                                <img
                                    src={
                                        !data.image
                                            ? "/images/no-photo.png"
                                            : typeof data.image === "string"
                                            ? `/storage/images/${data.image}`
                                            : URL.createObjectURL(data.image)
                                    }
                                    alt="photo"
                                    className="size-80 object-cover rounded z-0"
                                />
                                <div className="absolute right-2 top-2 z-10 size-8 bg-white rounded-full flex justify-center items-center">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        height="24px"
                                        viewBox="0 -960 960 960"
                                        width="24px"
                                        fill="#000"
                                    >
                                        <path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                    </svg>
                                </div>
                            </label>
                            <div className="hidden">
                                <Input
                                    id="image"
                                    darkMode={false}
                                    label="Imagen"
                                    name="image"
                                    type="file"
                                    onChange={(e) =>
                                        setData(
                                            "image",
                                            e.target.files?.[0] || null
                                        )
                                    }
                                    errorMessage={errors.image}
                                    errorActive={!!errors.image}
                                    ref={fileInputRef}
                                />
                            </div>
                        </div>
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
                    </div>
                    <div>
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
                        <Input
                            darkMode={false}
                            type="number"
                            name={"stock_actual"}
                            label={"Stock actual"}
                            value={data.stock_actual}
                            onChange={(e) =>
                                setData(
                                    "stock_actual",
                                    parseInt(e.target.value)
                                )
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
                                setData(
                                    "precio_detal",
                                    parseInt(e.target.value)
                                )
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
                                setData(
                                    "precio_embalaje",
                                    parseInt(e.target.value)
                                )
                            }
                            errorMessage={errors.precio_embalaje}
                            errorActive={!!errors.precio_embalaje}
                        />
                    </div>
                    <div>
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
                                        setData(
                                            "marca_id",
                                            parseInt(e.target.value)
                                        )
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
                                        setData(
                                            "embalaje_id",
                                            parseInt(e.target.value)
                                        )
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

                        <div>
                            <ToggleSwitch
                                name="active"
                                checked={data.active}
                                onChange={(val) => setData("active", val)}
                                label="Producto activo"
                                darkMode={false}
                            />
                            {errors.active ? (
                                <p className="text-sm text-red-700 font-semibold mt-1">
                                    {errors.active}
                                </p>
                            ) : null}
                        </div>
                    </div>
                </div>

                <div className="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <Button
                        type="submit"
                        variant="purple"
                        disabled={processing}
                    >
                        {productId ? "Modificar" : "Aceptar"}
                    </Button>
                    <Button type="button" variant="light" onClick={cancel}>
                        Cancelar
                    </Button>
                </div>
            </form>
        </div>
    );
}
