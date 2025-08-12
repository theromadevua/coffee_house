

export interface IDish {
    id: number;
    name: string;
    description: string;
    price: number;
    category?: { id: number, name: string};
}