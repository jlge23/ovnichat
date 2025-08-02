export function capitalizeWords(str: string): string {
    return str
        .split(" ") // separar por espacios
        .map((word) => {
            if (word.length === 0) return word; // evitar palabras vacías
            return word[0].toUpperCase() + word.slice(1).toLowerCase();
        })
        .join(" ");
}
