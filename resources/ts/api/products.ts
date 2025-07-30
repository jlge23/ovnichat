import axios from "axios";

type Category = {
    id: number;
    nombre: string;
    descripcion: string;
    created_at: Date;
    updated_at: Date;
};

type Packaging = {
    id: number;
    nombre: string;
    descripcion: string;
    created_at: Date;
    updated_at: Date;
};

type Brand = {
    id: number;
    nombre: string;
    created_at: Date;
    updated_at: Date;
};

type Provider = {
    id: number;
    nombre: string;
    created_at: Date;
    updated_at: Date;
};

type Metric = {
    id: number;
    nombre: string;
    simbolo: string;
    created_at: Date;
    updated_at: Date;
};

export type ProductProps = {
    id: number;
    gtin: string;
    nombre: string;
    codigo_sku: string;
    descripcion: string;
    categoria: Category;
    active: boolean;
    image: string;
    marca: Brand;
    proveedor: Provider;
    unidad_medida: Metric;
    stock_actual: number;
    embalaje: Packaging;
    unidades_por_embalaje: number;
    precio_detal: number;
    precio_embalaje: number;
    costo_detal: number;
    marca_id: number;
    categoria_id: number;
    proveedor_id: number;
    unidad_medida_id: number;
    embalaje_id: number;
};

type ProductSelectOptions = {
    categorias: Category[];
    embalajes: Packaging[];
    marcas: Brand[];
    proveedores: Provider[];
    unidadesMedidas: Metric[];
};

export type ProductForm = Omit<
    ProductProps,
    | "id"
    | "image"
    | "codigo_sku"
    | "categoria"
    | "marca"
    | "proveedor"
    | "unidad_medida"
    | "embalaje"
> & {
    image: File | string | null;
};

export async function fetchProductSelectOptions(): Promise<ProductSelectOptions> {
    const response = await axios.get("/productos/create");
    return response.data;
}
