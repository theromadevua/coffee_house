import { ICategory } from "../../shared/interfaces/category/ICategory";

export const categoryAdminTableColumns = [
    { header: 'ID', accessor: (cat: ICategory) => cat.id },
    { header: 'Name', accessor: (cat: ICategory) => cat.name },
    { header: 'Description', accessor: (cat: ICategory) => cat.description || '—' },
  ];