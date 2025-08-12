import { IDish } from "../../shared/interfaces/dish/IDish";


export const dishAdminPageColumns = [
    { header: 'ID', accessor: (dish: IDish) => dish.id },
    { header: 'Name', accessor: (dish: IDish) => dish.name },
    { header: 'Category', accessor: (dish: IDish) => dish.category?.name || 'N/A' },
    { header: 'Price', accessor: (dish: IDish) => `$${dish.price}` },
  ];