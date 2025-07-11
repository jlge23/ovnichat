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
    tipo_embalaje: string;
    descripcion: string;
    created_at: Date;
    updated_at: Date;
};

type Brand = {
    id: number;
    marca: string;
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

type ProductSelectOptions = {
    categorias: Category[];
    embalajes: Packaging[];
    marcas: Brand[];
    proveedores: Provider[];
    unidadesMedidas: Metric[];
};

export async function fetchProductSelectOptions(): Promise<ProductSelectOptions> {
    const response = await axios.get("/productos/create");
    return response.data;
}
