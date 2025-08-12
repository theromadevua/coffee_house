import { useEffect } from 'react';
import { useCategoryStore } from '../../../store/categoryStore';
import { ICategory } from '../../shared/interfaces/category/ICategory';
import { useSearch } from '../../shared/hooks/useSearch';
import { useAdmin } from '../../shared/hooks/useAdmin';

export const useCategoryAdmin = () => {
  const { categories, isLoading, error, fetchCategories, deleteCategory } = useCategoryStore();

  useEffect(() => {
    fetchCategories();
  }, [fetchCategories]); 

  const { searchQuery, handleSearch, filteredData: filteredCategories } = useSearch<ICategory>({
    data: categories,
    searchKeys: ['name', 'description'],
  });

  const crudHandlers = useAdmin<ICategory>(deleteCategory);

  return {
    isLoading,
    error,
    filteredCategories,
    searchQuery,
    handleCategorySearch: handleSearch, 
    ...crudHandlers,
  };
};